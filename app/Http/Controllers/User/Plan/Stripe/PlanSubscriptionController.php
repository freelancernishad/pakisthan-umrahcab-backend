<?php

namespace App\Http\Controllers\User\Plan\Stripe;

use Stripe\Stripe;
use App\Models\Plan\Plan;
use Illuminate\Http\Request;
use App\Helpers\StripeHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan\PlanSubscription;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session as StripeSession;
use App\Http\Requests\User\Plan\Stripe\PurchasePlanRequest;

class PlanSubscriptionController extends Controller
{
    public function PurchasePlan(PurchasePlanRequest $request, \FreelancerNishad\Stripe\Services\StripeService $stripeService)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $paymentType = $request->payment_type ?? 'single'; // single or recurring
        $successUrl = $request->success_url ?? url('/payment/success');
        $cancelUrl = $request->cancel_url ?? url('/payment/cancel');

        // Check for existing active subscription
        $existingSub = $request->user()->planSubscriptions()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->first();

        // If user has an active subscription to the SAME plan, block it.
        if ($existingSub && $existingSub->plan_id == $plan->id) {
            return response()->json(['error' => 'You are already subscribed to this plan.'], 400);
        }
        
        // If user has an active subscription to a DIFFERENT plan, we allow it (Upgrade/Downgrade flow).
        // The old one will be canceled in the webhook listener upon successful payment.

        $discountedPrice = $plan->discounted_price;
        $couponId = null;
        $extraParams = [];

        // --- PRORATION LOGIC START ---
        $prorationDiscount = 0;
        if ($existingSub && $existingSub->plan_id != $plan->id) {
            // Calculate unused value
            // Formula: (Original Amount paid / Total Billing Days) * Remaining Days
            
            // Determine total days in the billing cycle
            $startDate = \Carbon\Carbon::parse($existingSub->start_date);
            $endDate = \Carbon\Carbon::parse($existingSub->end_date);
            $totalDays = $startDate->diffInDays($endDate);
            if ($totalDays == 0) $totalDays = 1; // Prevent division by zero
            
            // Determine remaining days
            $remainingDays = now()->diffInDays($endDate, false); // false = return negative if past
            
            if ($remainingDays > 0) {
                $amountPaid = $existingSub->final_amount; // Use what they actually paid
                $dailyRate = $amountPaid / $totalDays;
                $unusedValue = $dailyRate * $remainingDays;
                
                $prorationDiscount = round($unusedValue, 2);
                
                if ($prorationDiscount > 0) {
                    // Downgrade Protection: checking if unused value > new plan price
                    if ($unusedValue > $discountedPrice) {
                         return response()->json([
                             'error' => "Downgrade Blocked: Your current unused credit (\${$unusedValue}) exceeds the new plan price (\${$discountedPrice}). You would lose value."
                         ], 400);
                    }

                    // Cap discount at new plan price (shouldn't be hit due to above check, but safe to keep)
                    if ($prorationDiscount > $discountedPrice) {
                        $prorationDiscount = $discountedPrice;
                    }

                    // Create Stripe Coupon for this specific proration
                    try {
                        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                        $stripeCoupon = \Stripe\Coupon::create([
                            'amount_off' => (int)($prorationDiscount * 100),
                            'currency' => 'usd',
                            'duration' => 'once',
                            'name' => 'Proration Credit: ' . $existingSub->plan->name,
                        ]);
                        
                        // Add to extraParams to be applied in checkout
                        if (!isset($extraParams['discounts'])) {
                            $extraParams['discounts'] = [];
                        }
                        $extraParams['discounts'][] = ['coupon' => $stripeCoupon->id];
                        
                        Log::info("Proration calculated. Unused Value: \${$unusedValue}, Discount Applied: \${$prorationDiscount}, Coupon: {$stripeCoupon->id}");
                        
                    } catch (\Exception $e) {
                        Log::error("Failed to create proration coupon: " . $e->getMessage());
                    }
                }
            }
        }
        // --- PRORATION LOGIC END ---

        // Apply Internal Coupon if provided
        if ($request->coupon_code) {
            $coupon = \App\Models\Coupon\Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValid() && !$coupon->hasUsageLimit()) {
                $discount = $coupon->getDiscountAmount($discountedPrice);
                
                if ($paymentType === 'subscription') {
                    // For Recurring: Charge Regular Price next time, apply discount ONCE now
                    try {
                        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                        $stripeCoupon = \Stripe\Coupon::create([
                            'amount_off' => (int)($discount * 100),
                            'currency' => 'usd',
                            'duration' => 'once',
                            'name' => 'First-Time Discount: ' . $coupon->code,
                        ]);
                        $extraParams['discounts'] = [['coupon' => $stripeCoupon->id]];
                        Log::info("Created one-time Stripe coupon for recurring: {$stripeCoupon->id}");
                    } catch (\Exception $e) {
                        Log::error("Failed to create Stripe coupon: " . $e->getMessage());
                    }
                } else {
                    // For Single: Just reduce the price
                    $discountedPrice = max(0, $discountedPrice - $discount);
                }
                
                $couponId = $coupon->id;
                Log::info("Applied internal coupon: {$request->coupon_code}, Discount: {$discount}");
            } else {
                return response()->json(['error' => 'Invalid or expired coupon code'], 422);
            }
        }

        $metadata = [
            'plan_id' => $plan->id,
            'coupon_id' => $couponId,
        ];

        try {
            if ($paymentType === 'single') {
                $items = [[
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => (int)($discountedPrice * 100),
                        'product_data' => [
                            'name' => $plan->name,
                            'description' => 'One-Time Purchase' . ($couponId ? " (Coupon Applied)" : ""),
                        ],
                    ],
                    'quantity' => 1,
                ]];
                
                $session = $stripeService->createCheckoutSession(
                    Auth::user(),
                    $items,
                    $successUrl,
                    $cancelUrl,
                    $request->boolean('save_card', false),
                    $metadata,
                    $extraParams
                );
            } else {
                // Subscription mode - unit_amount is the REGULAR price
                $priceData = [
                    'amount' => (int)($plan->discounted_price * 100),
                    'currency' => 'usd',
                    'interval' => $plan->duration_type === 'year' ? 'year' : 'month',
                    'interval_count' => (int)$plan->duration ?: 1,
                    'product_name' => $plan->name . ($couponId ? " (First Invoice Discount Applied)" : ""),
                ];
                
                $session = $stripeService->createCustomSubscriptionSession(
                    Auth::user(),
                    $priceData,
                    $successUrl,
                    $cancelUrl,
                    $metadata,
                    $extraParams
                );
            }

            return response()->json([
                'url' => $session->url,
                'id' => $session->id,
                'mode' => $paymentType === 'single' ? 'payment' : 'subscription',
                'final_price' => $discountedPrice,
            ]);
        } catch (\Exception $e) {
            Log::error("Purchase failed: " . $e->getMessage());
            return response()->json([
                'error' => 'Unable to create Stripe checkout session: ' . $e->getMessage()
            ], 500);
        }
    }


     /**
     * Cancel a subscription
     *
     * @param Request $request
     * @param int $subscriptionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelSubscription(Request $request, int $subscriptionId)
    {
        try {
            $subscription = PlanSubscription::findOrFail($subscriptionId);

            // Verify ownership
            if ($subscription->user_id != Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $cancelImmediately = $request->input('immediately', false);
            $result = $subscription->cancelSubscription($cancelImmediately);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'status' => $result['status'],
                'end_date' => $subscription->end_date,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }



}

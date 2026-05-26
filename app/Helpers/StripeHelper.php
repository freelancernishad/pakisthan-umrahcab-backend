<?php
namespace App\Helpers;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Stripe\Subscription as StripeSubscription;

class StripeHelper
{
    /**
     * Create a Stripe checkout session dynamically
     *
     * @param array $params {
     *      @type \Illuminate\Http\Request $request HTTP request instance
     *      @type array $lineItems Line items for purchase
     *      @type array $metadata Additional metadata
     *      @type string $success_url Success URL
     *      @type string $cancel_url Cancel URL
     *      @type string $currency Currency code (default: USD)
     *      @type array $payment_method_types Payment methods (default: ['card'])
     *      @type string $billing_interval Billing interval for subscriptions (default: 'month')
     *      @type int $interval_count Number of intervals between billing (default: 1)
     * }
     * @return \Stripe\Checkout\Session
     * @throws \Exception
     */
    public static function createCheckoutSession(array $params): StripeSession
    {
        // Set Stripe API key
        Stripe::setApiKey(config('STRIPE_SECRET'));

        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('No authenticated user found');
        }

        // Get request instance
        $request = $params['request'] ?? null;
        if (!$request) {
            throw new \InvalidArgumentException('Request parameter is required');
        }

        // Determine payment type and mode
        $paymentType = $request->input('payment_type', 'single');
        $mode = $paymentType === 'single' ? 'payment' : 'subscription';

        // Set default values
        $defaults = [
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'billing_interval' => 'month',
            'interval_count' => 1,
        ];

        $params = array_merge($defaults, $params);

        // Validate required parameters
        $required = ['lineItems', 'metadata', 'success_url', 'cancel_url'];
        foreach ($required as $key) {
            if (empty($params[$key])) {
                throw new \InvalidArgumentException("Missing required parameter: {$key}");
            }
        }

        // Merge user ID into metadata
        $metadata = array_merge(
            $params['metadata'],
            ['user_id' => $user->id]
        );

        // Process line items to add recurring configuration for subscriptions
        $lineItems = $params['lineItems'];
        if ($mode === 'subscription') {
            foreach ($lineItems as &$item) {
                if (isset($item['price_data'])) {
                    // Add recurring configuration for subscription mode
                    $item['price_data']['recurring'] = [
                        'interval' => $params['billing_interval'],
                        'interval_count' => $params['interval_count'],
                    ];
                }
            }
        }

        // Prepare session parameters
        $sessionParams = [
            'payment_method_types' => $params['payment_method_types'],
            'mode' => $mode,
            'customer_email' => $user->email,
            'line_items' => $lineItems,
            'metadata' => $metadata,
            'success_url' => $params['success_url'],
            'cancel_url' => $params['cancel_url'],
        ];

        // Create Stripe session
        try {
            return StripeSession::create($sessionParams);
        } catch (\Exception $e) {
            Log::error("Stripe checkout session creation failed: " . $e->getMessage());
            throw $e;
        }
    }




       /**
     * Cancel a Stripe subscription
     *
     * @param string $subscriptionId Stripe subscription ID
     * @param bool $cancelImmediately Whether to cancel immediately or at period end
     * @return array
     * @throws \Exception
     */
    public static function cancelSubscription(string $subscriptionId, bool $cancelImmediately = false): array
    {
        // Set Stripe API key
        Stripe::setApiKey(config('STRIPE_SECRET'));

        try {
            // Retrieve the subscription
            $subscription = StripeSubscription::retrieve($subscriptionId);

            if ($cancelImmediately) {
                // Cancel immediately
                $subscription->cancel();
                $status = 'canceled';
                $message = 'Subscription canceled immediately';
            } else {
                // Cancel at period end
                $subscription->cancel_at_period_end = true;
                $subscription->save();
                $status = 'canceling';
                $message = 'Subscription will be canceled at the end of the current billing period';
            }

            return [
                'success' => true,
                'status' => $status,
                'message' => $message,
                'current_period_end' => $subscription->current_period_end,
            ];

        } catch (\Exception $e) {
            Log::error("Failed to cancel Stripe subscription: " . $e->getMessage());
            throw $e;
        }
    }

}

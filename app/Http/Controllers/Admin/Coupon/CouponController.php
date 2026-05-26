<?php

namespace App\Http\Controllers\Admin\Coupon;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Coupon\Coupon;


use App\Models\Coupon\CouponUsage;
use App\Http\Controllers\Controller;
use App\Models\Coupon\CouponAssociation;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Coupon\AdminCouponStoreRequest;
use App\Http\Requests\Admin\Coupon\AdminCouponApplyRequest;
use App\Http\Requests\Admin\Coupon\AdminCouponCheckRequest;

class CouponController extends Controller
{
    // Store a new coupon
    public function store(AdminCouponStoreRequest $request)
    {

        $validated = $request->all();
        $coupon = Coupon::create($validated);

        if ($request->has('associations') && !empty($request->associations)) {
            foreach ($request->associations as $association) {
                CouponAssociation::create([
                    'coupon_id' => $coupon->id,
                    'item_id' => $association['item_id'],
                    'item_type' => $association['item_type'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Coupon created successfully',
            'coupon' => $coupon
        ], 201);
    }

    // List all coupons
    public function index()
    {
        $coupons = Coupon::with('associations')->paginate(10); // Paginated list
        return response()->json($coupons, 200);
    }

    // Edit an existing coupon
    public function update(AdminCouponStoreRequest $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->update($request->all());

        if ($request->has('associations')) {
            CouponAssociation::where('coupon_id', $id)->delete(); // Remove old associations
            foreach ($request->associations as $association) {
                CouponAssociation::create([
                    'coupon_id' => $coupon->id,
                    'item_id' => $association['item_id'],
                    'item_type' => $association['item_type'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Coupon updated successfully',
            'coupon' => $coupon
        ], 200);
    }

    // Delete a coupon
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        CouponAssociation::where('coupon_id', $id)->delete(); // Delete related associations
        $coupon->delete();

        return response()->json([
            'message' => 'Coupon deleted successfully'
        ], 200);
    }

    // Apply coupon to a user's order
    public function apply(AdminCouponApplyRequest $request)
    {

        $validated = $request->all();
        $coupon = Coupon::where('code', $validated['coupon_code'])->first();

        if (!$coupon || !$coupon->is_active) {
            return response()->json(['message' => 'Coupon is invalid or expired'], 400);
        }

        $now = now();
        if ($coupon->valid_from > $now || $coupon->valid_until < $now) {
            return response()->json(['message' => 'Coupon is not valid for the current date'], 400);
        }

        $validAssociation = CouponAssociation::where('coupon_id', $coupon->id)
            ->where(function ($query) use ($validated) {
                if (isset($validated['user_id'])) {
                    $query->where('item_id', $validated['user_id'])->where('item_type', 'user');
                }
                if (isset($validated['package_id'])) {
                    $query->where('item_id', $validated['package_id'])->where('item_type', 'package');
                }
                if (isset($validated['service_id'])) {
                    $query->where('item_id', $validated['service_id'])->where('item_type', 'service');
                }
                if (isset($validated['plan_id'])) {
                    $query->where('item_id', $validated['plan_id'])->where('item_type', 'plan');
                }
            })
            ->exists();

        if (!$validAssociation) {
            return response()->json(['message' => 'Coupon is not valid for the provided item'], 400);
        }

        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = ($validated['order_total'] * $coupon->value) / 100;
        } elseif ($coupon->type === 'flat') {
            $discount = $coupon->value;
        }

        $discounted_total = $validated['order_total'] - $discount;

        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'order_total' => $validated['order_total'],
            'discount' => $discount,
        ]);

        return response()->json([
            'message' => 'Coupon applied successfully',
            'discount' => $discount,
            'discounted_total' => $discounted_total,
        ]);
    }


    public function checkCoupon(AdminCouponCheckRequest $request)
    {

        // Fetch coupon by code
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        // Check if the coupon is valid using the model's method
        if (!$coupon->isValid()) {
            return response()->json([
                'message' => 'Coupon is inactive or expired.',
                'coupon' => $coupon
            ], 400);
        }

        // Check usage limit using the model's method
        if ($coupon->hasUsageLimit()) {
            return response()->json([
                'message' => 'Coupon usage limit has been reached.',
                'coupon' => $coupon
            ], 400);
        }

        // Check if the coupon has any associations
        $hasAssociations = $coupon->associations()->exists();

        if ($hasAssociations) {
            // Validate associations if item_id and item_type are provided
            if ($request->has(['item_id', 'item_type'])) {
                $associationExists = $coupon->associations()
                    ->where('item_id', $request->item_id)
                    ->where('item_type', $request->item_type)
                    ->exists();

                if (!$associationExists) {
                    return response()->json([
                        'message' => 'Coupon is not valid for this item.',
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'Coupon requires an associated item.',
                ], 400);
            }
        }

        // Calculate discount using the model's method
        $productAmount = $request->product_amount;
        $discountedAmount = $coupon->getDiscountAmount($productAmount);
        $finalAmount = $productAmount - $discountedAmount;

        return response()->json([
            'message' => 'Coupon is valid.',
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'product_amount' => $productAmount,
            'discount' => $coupon->type === 'percentage' ? $coupon->value . '%' : $coupon->value,
            'discounted_amount' => $discountedAmount,
            'final_amount' => $finalAmount,
        ], 200);
    }







}

<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class AdminCouponApplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coupon_code' => 'required|string|exists:coupons,code',
            'order_total' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'package_id' => 'nullable|exists:packages,id',
            'service_id' => 'nullable|exists:services,id',
            'plan_id' => 'nullable|exists:plans,id',
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class AdminCouponCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coupon_code' => 'required|string|exists:coupons,code',
            'user_id' => 'nullable|exists:users,id',
            'item_id' => 'nullable|integer',
            'item_type' => 'nullable|in:user,package,service,plan',
            'product_amount' => 'required|numeric|min:0',
        ];
    }
}

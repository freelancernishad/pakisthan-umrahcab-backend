<?php

namespace App\Http\Requests\User\Plan\Stripe;

use Illuminate\Foundation\Http\FormRequest;

class PurchasePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_id' => 'required|exists:plans,id',
            'payment_type' => 'nullable|in:single,subscription',
            'success_url' => 'required|url',
            'cancel_url' => 'required|url',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ];
    }
}

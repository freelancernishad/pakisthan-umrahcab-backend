<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class AdminCouponStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'code' => 'required|string|unique:coupons,code' . ($id ? ',' . $id : ''),
            'type' => 'required|string|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
            'associations' => 'nullable|array',
            'associations.*.item_id' => 'required_with:associations|integer',
            'associations.*.item_type' => 'required_with:associations|in:user,package,service,plan',
        ];
    }
}

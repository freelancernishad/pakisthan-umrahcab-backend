<?php

namespace App\Http\Requests\Admin\Plans;

use Illuminate\Foundation\Http\FormRequest;

class AdminPlanStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'duration' => 'required|string',
            'original_price' => 'required|numeric',
            'monthly_price' => 'nullable|numeric',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'features' => 'required|array',
            'features.*.key' => 'required|string|exists:plan_features,key',
            'features.*.value' => 'nullable|string',
            'features.*.amount' => 'nullable|string',
        ];
    }
}

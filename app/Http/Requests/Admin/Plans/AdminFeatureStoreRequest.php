<?php

namespace App\Http\Requests\Admin\Plans;

use Illuminate\Foundation\Http\FormRequest;

class AdminFeatureStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'key' => 'required|string|unique:plan_features,key,' . $id,
            'title_template' => 'required|string',
            'unit' => 'nullable|string',
        ];
    }
}

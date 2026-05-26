<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*' => 'required|array',
            '*.key' => 'required|string',
            '*.value' => 'required|string',
        ];
    }
}

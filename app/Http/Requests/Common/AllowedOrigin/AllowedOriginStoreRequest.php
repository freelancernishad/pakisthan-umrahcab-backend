<?php

namespace App\Http\Requests\Common\AllowedOrigin;

use Illuminate\Foundation\Http\FormRequest;

class AllowedOriginStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('origin'); // Assumed route parameter name
        return [
            'origin_url' => 'required|url|unique:allowed_origins,origin_url' . ($id ? ',' . $id : ''),
        ];
    }
}

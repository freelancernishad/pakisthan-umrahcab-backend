<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdatePhotosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|url',
        ];
    }

    public function messages(): array
    {
        return [
            'photos.required' => 'You must provide at least one photo URL.',
            'photos.*.required' => 'Each photo must have a URL.',
            'photos.*.url' => 'Each photo URL must be valid.',
        ];
    }
}

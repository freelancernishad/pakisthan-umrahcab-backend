<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required_without_all:access_token,identity_token|string|email',
            'password' => 'required_without_all:access_token,identity_token|string',
            'access_token' => 'string',
            'identity_token' => 'string',
        ];
    }
}

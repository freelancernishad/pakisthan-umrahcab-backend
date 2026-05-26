<?php

namespace App\Http\Requests\Admin\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => 'nullable|string|min:8',
        ];
    }
}

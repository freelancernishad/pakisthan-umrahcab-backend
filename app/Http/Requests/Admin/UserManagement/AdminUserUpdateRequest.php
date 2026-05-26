<?php

namespace App\Http\Requests\Admin\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'is_active' => 'sometimes|boolean',
            'is_blocked' => 'sometimes|boolean',
            'role' => 'sometimes|string|in:user,moderator,admin',
            'notes' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20'
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserBulkActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => 'required|string|in:activate,deactivate,block,unblock',
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
        ];
    }
}

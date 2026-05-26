<?php

namespace App\Http\Requests\Admin\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|mimes:xlsx,csv'
        ];
    }
}

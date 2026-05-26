<?php

namespace App\Http\Requests\Common\SupportAndConnect\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SupportTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:open,closed,pending,replay',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ];
    }
}

<?php

namespace App\Http\Requests\Common\SupportAndConnect;

use Illuminate\Foundation\Http\FormRequest;

class SupportTicketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ];
    }
}

<?php

namespace App\Http\Requests\Common\SupportAndConnect\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SupportTicketReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reply' => 'required|string',
            'status' => 'required|string|in:open,closed,pending,replay',
            'reply_id' => 'nullable|exists:replies,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ];
    }
}

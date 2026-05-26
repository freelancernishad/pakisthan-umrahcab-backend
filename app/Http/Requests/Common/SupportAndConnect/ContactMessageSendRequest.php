<?php

namespace App\Http\Requests\Common\SupportAndConnect;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string',
            'email'     => 'required|email',
            'subject'   => 'required|string',
            'message'   => 'required|string',
        ];
    }
}

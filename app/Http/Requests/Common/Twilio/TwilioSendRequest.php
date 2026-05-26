<?php

namespace App\Http\Requests\Common\Twilio;

use Illuminate\Foundation\Http\FormRequest;

class TwilioSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'message' => 'required|string',
        ];
    }
}

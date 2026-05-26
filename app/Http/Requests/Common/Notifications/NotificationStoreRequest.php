<?php

namespace App\Http\Requests\Common\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class NotificationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'type' => 'nullable|string',
            'related_model' => 'nullable|string',
            'related_model_id' => 'nullable|integer',
        ];
    }
}

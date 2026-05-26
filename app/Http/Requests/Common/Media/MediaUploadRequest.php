<?php

namespace App\Http\Requests\Common\Media;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required_without:file_url|nullable|image|max:10240',
            'file_url' => 'required_without:file|nullable|url',
        ];
    }
}

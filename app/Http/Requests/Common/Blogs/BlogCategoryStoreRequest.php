<?php

namespace App\Http\Requests\Common\Blogs;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:255',
            'slug' => 'sometimes|required|string|unique:blog_categories,slug' . ($id ? ',' . $id : ''),
            'parent_id' => 'nullable|exists:blog_categories,id' . ($id ? '|not_in:' . $id : ''),
        ];
    }
}

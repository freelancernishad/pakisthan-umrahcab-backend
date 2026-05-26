<?php

namespace App\Http\Requests\Common\Blogs;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_posts,slug' . ($id ? ',' . $id : ''),
            'content' => 'required|string',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:blog_categories,id',
            'banner_image' => 'nullable|string',
            'status' => 'nullable|in:draft,published,archived',
        ];
    }
}

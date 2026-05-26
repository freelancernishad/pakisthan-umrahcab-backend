<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'banner_image',
        'status',
    ];


    /**
     * Many-to-many with BlogCategory
     */
    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_blog_post', 'blog_post_id', 'blog_category_id');
    }
}

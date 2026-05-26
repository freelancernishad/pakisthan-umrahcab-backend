<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';

    protected $fillable = ['name', 'slug', 'parent_id'];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id')->with('children'); // Recursive relationship
    }

    /**
     * Many-to-many with BlogPost
     */
    public function blogs()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_category_blog_post', 'blog_category_id', 'blog_post_id');
    }

       /**
     * Get all descendants of a category (including self).
     */
    public function descendantsAndSelf()
    {
        // Start with the current category (self) and include all children recursively
        $descendants = collect([$this]);

        foreach ($this->children as $child) {
            $descendants = $descendants->merge($child->descendantsAndSelf());
        }

        return $descendants;
    }
}


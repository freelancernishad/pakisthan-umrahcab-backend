<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'union_id',
        'type',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopeGlobal($query)
    {
        return $query->where('type', 'global');
    }

    public function scopeUnion($query, $unionId)
    {
        return $query->where('type', 'union')->where('union_id', $unionId);
    }
}

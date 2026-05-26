<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'path',
        'is_primary',
        'is_approved',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

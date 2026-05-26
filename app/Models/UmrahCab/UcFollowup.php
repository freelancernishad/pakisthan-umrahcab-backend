<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcFollowup extends Model
{
    use HasFactory;

    protected $table = 'uc_followups';

    protected $fillable = [
        'custom_id',
        'title',
        'agent',
        'contact',
        'date',
        'status',
        'notes'
    ];
}

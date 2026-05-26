<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcFleet extends Model
{
    use HasFactory;

    protected $table = 'uc_fleet';

    protected $fillable = [
        'model',
        'count',
        'active'
    ];

    protected $casts = [
        'count' => 'integer',
        'active' => 'integer',
    ];
}

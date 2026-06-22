<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcHotel extends Model
{
    use HasFactory;

    protected $table = 'uc_hotels';

    protected $fillable = [
        'name',
        'city',
        'active'
    ];

    protected $casts = [
        'active' => 'integer',
    ];
}

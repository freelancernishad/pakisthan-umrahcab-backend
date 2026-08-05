<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcLocation extends Model
{
    use HasFactory;

    protected $table = 'uc_locations';

    protected $fillable = [
        'name',
        'type'
    ];
}

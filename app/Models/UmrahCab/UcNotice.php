<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcNotice extends Model
{
    use HasFactory;

    protected $table = 'uc_notices';

    protected $fillable = [
        'custom_id',
        'title',
        'date',
        'priority',
        'target',
        'content'
    ];
}

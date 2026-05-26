<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcLedger extends Model
{
    use HasFactory;

    protected $table = 'uc_ledgers';

    protected $fillable = [
        'custom_id',
        'company',
        'date',
        'description',
        'debit',
        'credit',
        'balance'
    ];

    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
        'balance' => 'float',
    ];
}

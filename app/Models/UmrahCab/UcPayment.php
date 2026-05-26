<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcPayment extends Model
{
    use HasFactory;

    protected $table = 'uc_payments';

    protected $fillable = [
        'custom_id',
        'company',
        'date',
        'method',
        'amount',
        'currency',
        'status'
    ];

    protected $casts = [
        'amount' => 'float',
    ];
}

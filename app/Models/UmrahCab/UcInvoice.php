<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcInvoice extends Model
{
    use HasFactory;

    protected $table = 'uc_invoices';

    protected $fillable = [
        'invoice_code',
        'customer',
        'date',
        'amount',
        'balance',
        'status'
    ];

    protected $casts = [
        'amount' => 'float',
        'balance' => 'float',
    ];
}

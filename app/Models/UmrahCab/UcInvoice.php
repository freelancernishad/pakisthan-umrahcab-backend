<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcInvoice extends Model
{
    use HasFactory;

    protected $table = 'uc_invoices';

    protected $fillable = [
        'customer_id',
        'invoice_code',
        'customer',
        'date',
        'period',
        'amount',
        'balance',
        'status',
        'type',
        'remarks',
        'entered_by'
    ];

    protected $casts = [
        'amount' => 'float',
        'balance' => 'float',
    ];

    public function customer_relation()
    {
        return $this->belongsTo(UcCustomer::class, 'customer_id');
    }
}

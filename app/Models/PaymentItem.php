<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'item_id',
        'name',
        'type',
        'amount',
        'quantity',
        'status',
        'meta',
        'date',
        'time',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

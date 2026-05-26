<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $fillable = [
        'user_id',
        'payable_id',
        'payable_type',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'stripe_invoice_id',
        'stripe_subscription_id',
        'status',
        'date',
        'time',
        'webhook_received_at',
        'webhook_status',
        'webhook_signature',
        'meta',
        'gateway_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
        'gateway_response' => 'array',
        'webhook_received_at' => 'datetime',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(PaymentItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }
}

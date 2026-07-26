<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcService extends Model
{
    use HasFactory;

    protected $table = 'uc_services';

    protected $fillable = [
        'customer_id',
        'custom_id',
        'name',
        'type',
        'description',
        'base_price',
        'status',
        'pickup',
        'driver_cash',
        'date',
        'time',
        'reminder1_sent',
        'reminder2_sent',
        'reminder3_sent'
    ];

    protected $casts = [
        'base_price' => 'float',
        'driver_cash' => 'float',
        'reminder1_sent' => 'boolean',
        'reminder2_sent' => 'boolean',
        'reminder3_sent' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(UcCustomer::class, 'customer_id');
    }
}

<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcIndividualOrder extends Model
{
    use HasFactory;

    protected $table = 'uc_individual_orders';

    protected $fillable = [
        'order_code',
        'pickup',
        'destination',
        'date',
        'time',
        'passengers',
        'car_type',
        'car_price',
        'full_name',
        'email',
        'whatsapp',
        'flight_no',
        'notes',
        'status',
        'payment_status'
    ];

    protected $casts = [
        'car_price' => 'float',
        'date' => 'date:Y-m-d'
    ];

    public function invoice()
    {
        return $this->hasOne(UcInvoice::class, 'individual_order_id');
    }
}

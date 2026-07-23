<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcBooking extends Model
{
    use HasFactory;

    protected $table = 'uc_bookings';

    protected $fillable = [
        'customer_id',
        'driver_id',
        'booking_code',
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
        'payment_method',
        'received_amount',
        'pending_amount',
        'driver_trip_status'
    ];

    protected $casts = [
        'car_price' => 'float',
        'received_amount' => 'float',
        'pending_amount' => 'float',
        'driver_id' => 'integer',
    ];

    public function driver()
    {
        return $this->belongsTo(UcDriver::class, 'driver_id');
    }
}

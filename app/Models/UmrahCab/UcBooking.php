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
        'status'
    ];

    protected $casts = [
        'car_price' => 'float',
    ];
}

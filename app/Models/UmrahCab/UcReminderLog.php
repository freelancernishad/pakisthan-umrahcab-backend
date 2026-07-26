<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcReminderLog extends Model
{
    use HasFactory;

    protected $table = 'uc_reminder_logs';

    protected $fillable = [
        'booking_id',
        'service_id',
        'type',
        'reminder_type',
        'recipient',
        'driver_name',
        'driver_trip_status'
    ];
}

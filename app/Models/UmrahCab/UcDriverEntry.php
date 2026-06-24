<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcDriverEntry extends Model
{
    use HasFactory;

    protected $table = 'uc_driver_entries';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'date',
        'trip',
        'hotel_drop_off',
        'agent',
        'rate',
        'voucher',
        'cash',
        'fuel',
        'parking',
        'wash',
        'oil_change',
        'car_maintenance',
        'waqas_received',
        'mic',
        'total',
        'is_locked'
    ];

    protected $casts = [
        'driver_id' => 'integer',
        'vehicle_id' => 'integer',
        'date' => 'date',
        'rate' => 'float',
        'voucher' => 'float',
        'cash' => 'float',
        'fuel' => 'float',
        'parking' => 'float',
        'wash' => 'float',
        'oil_change' => 'float',
        'car_maintenance' => 'float',
        'waqas_received' => 'float',
        'mic' => 'float',
        'total' => 'float',
        'is_locked' => 'boolean'
    ];

    public function driver()
    {
        return $this->belongsTo(UcDriver::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(UcFleet::class, 'vehicle_id');
    }
}

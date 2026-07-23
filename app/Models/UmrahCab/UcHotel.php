<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcHotel extends Model
{
    use HasFactory;

    protected $table = 'uc_hotels';

    protected $fillable = [
        'customer_id',
        'driver_id',
        'custom_id',
        'name',
        'city',
        'active',
        'check_in',
        'check_out'
    ];

    protected $casts = [
        'active' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(UcCustomer::class, 'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(UcDriver::class, 'driver_id');
    }
}

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
        'time'
    ];

    protected $casts = [
        'base_price' => 'float',
        'driver_cash' => 'float',
    ];

    public function customer()
    {
        return $this->belongsTo(UcCustomer::class, 'customer_id');
    }
}

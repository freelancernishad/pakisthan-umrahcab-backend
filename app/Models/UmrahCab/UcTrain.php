<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcTrain extends Model
{
    use HasFactory;

    protected $table = 'uc_trains';

    protected $fillable = [
        'customer_id',
        'driver_id',
        'custom_id',
        'train_no',
        'leg',
        'date',
        'time',
        'route',
        'status'
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

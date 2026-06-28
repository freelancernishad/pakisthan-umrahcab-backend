<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcPriceList extends Model
{
    use HasFactory;

    protected $table = 'uc_price_lists';

    protected $fillable = [
        'route',
        'group_name',
        'sedan_price',
        'sedan_dates',
        'suv_price',
        'suv_dates',
        'van_price',
        'van_dates',
        'coach_price',
        'coach_dates',
        'custom_prices'
    ];

    protected $casts = [
        'sedan_price' => 'float',
        'suv_price' => 'float',
        'van_price' => 'float',
        'coach_price' => 'float',
        'custom_prices' => 'array'
    ];
}

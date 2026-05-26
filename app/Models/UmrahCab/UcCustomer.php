<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcCustomer extends Model
{
    use HasFactory;

    protected $table = 'uc_customers';

    protected $fillable = [
        'custom_id',
        'name',
        'company',
        'contact',
        'registered_by',
        'last_update'
    ];
}

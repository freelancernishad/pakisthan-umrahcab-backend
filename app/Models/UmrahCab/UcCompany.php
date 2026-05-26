<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcCompany extends Model
{
    use HasFactory;

    protected $table = 'uc_companies';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'website',
        'address',
        'invoice',
        'vouchers',
        'reminders',
        'statement_status',
        'remarks'
    ];

    protected $casts = [
        'invoice' => 'boolean',
        'vouchers' => 'boolean',
        'reminders' => 'boolean',
    ];
}

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
        'agent_username',
        'agent_password',
        'phone',
        'email',
        'website',
        'logo_path',
        'address',
        'invoice',
        'vouchers',
        'reminders',
        'statement_status',
        'remarks',
        'ledger_frequency',
        'tomorrow_reminder',
        'exempt_bulk_lock'
    ];

    protected $casts = [
        'invoice' => 'boolean',
        'vouchers' => 'boolean',
        'reminders' => 'boolean',
        'tomorrow_reminder' => 'boolean',
        'exempt_bulk_lock' => 'boolean',
    ];
}

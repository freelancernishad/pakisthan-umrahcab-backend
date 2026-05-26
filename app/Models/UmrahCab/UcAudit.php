<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcAudit extends Model
{
    use HasFactory;

    protected $table = 'uc_audits';

    protected $fillable = [
        'custom_id',
        'user_session',
        'ip_location',
        'performed_action'
    ];
}

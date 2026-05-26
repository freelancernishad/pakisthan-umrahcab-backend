<?php

namespace App\Models\SupportAndConnect\Contacts;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'full_name', 'email', 'subject', 'message', 'email_sent',
    ];
}

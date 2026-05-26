<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcUser extends Model
{
    use HasFactory;

    protected $table = 'uc_users';

    protected $fillable = [
        'username',
        'password',
        'user_type',
        'company_id'
    ];

    protected $hidden = [
        'password',
    ];

    public function company()
    {
        return $this->belongsTo(UcCompany::class, 'company_id');
    }
}

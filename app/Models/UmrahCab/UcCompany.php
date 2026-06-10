<?php

namespace App\Models\UmrahCab;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UcCompany extends Authenticatable implements JWTSubject
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

    protected $hidden = [
        'agent_password',
    ];

    protected $casts = [
        'invoice' => 'boolean',
        'vouchers' => 'boolean',
        'reminders' => 'boolean',
        'tomorrow_reminder' => 'boolean',
        'exempt_bulk_lock' => 'boolean',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->agent_password;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'agent_username' => $this->agent_username,
            'guard' => 'company',
            'model' => UcCompany::class
        ];
    }
}


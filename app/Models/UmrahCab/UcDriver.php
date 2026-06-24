<?php

namespace App\Models\UmrahCab;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UcDriver extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'uc_drivers';

    protected $fillable = [
        'name',
        'username',
        'password',
        'phone',
        'license_no',
        'vehicle_id',
        'edit_rights'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'edit_rights' => 'boolean',
        'vehicle_id' => 'integer'
    ];

    public function vehicle()
    {
        return $this->belongsTo(UcFleet::class, 'vehicle_id');
    }

    public function entries()
    {
        return $this->hasMany(UcDriverEntry::class, 'driver_id');
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
            'username' => $this->username,
            'phone' => $this->phone,
            'guard' => 'driver',
            'model' => UcDriver::class
        ];
    }
}

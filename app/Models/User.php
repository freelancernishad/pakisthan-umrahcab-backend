<?php

namespace App\Models;

use App\Models\Plan\PlanSubscription;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',
        'otp_expires_at',
        'stripe_id',

        'is_active',
        'is_blocked',
        'role',
        'notes',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_blocked' => 'boolean',
            'last_login_at' => 'datetime',
        ];
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
            'email_verified' => !is_null($this->email_verified_at),
            'is_active' => $this->is_active,
            'is_blocked' => $this->is_blocked,
            'role' => $this->role,
            'last_login_at' => optional($this->last_login_at)->toDateTimeString(),
            "guard" => "user",
            "model" => User::class
        ];
    }





    public function photos()
    {
        return $this->hasMany(UserPhoto::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(UserPhoto::class)->where('is_primary', true);
    }

    protected $appends = [
        'profile_picture'
    ];

    // Accessor for profile picture
    public function getProfilePictureAttribute()
    {
        return $this->primaryPhoto?->path;
    }

    public function planSubscriptions()
    {
        return $this->hasMany(PlanSubscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->where('status', 'paid');
    }





}

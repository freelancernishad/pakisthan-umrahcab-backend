<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;

class Admin extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'otp',
        'otp_expires_at',
        'email_verification_hash',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'otp',
        'otp_expires_at',
        'email_verification_hash',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'email_verified' => !is_null($this->email_verified_at),
            "guard" => "admin",
            "model" => Admin::class
        ];
    }
}

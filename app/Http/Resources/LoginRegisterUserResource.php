<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginRegisterUserResource extends JsonResource
{
    protected $token;
    protected $message;
    protected $isError;

    public function __construct($resource, $token, $message = null, $isError = false)
    {
        parent::__construct($resource);
        $this->token = $token;
        $this->message = $message;
        $this->isError = $isError;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'email_verified' => !is_null($this->email_verified_at),
                'profile_picture' => $this->profile_picture,
                'is_active' => $this->is_active,
                'is_blocked' => $this->is_blocked,
                'role' => $this->role,
                'last_login_at' => optional($this->last_login_at)->toDateTimeString(),
            ],
            'Message' => $this->message,
            'isError' => $this->isError,
        ];
    }
}

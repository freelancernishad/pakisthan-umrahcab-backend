<?php

namespace App\Helpers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class HelpersFunctions
{
    /**
     * JWT token decode
     *
     * @param string|null $field
     * @return array|string|null
     */
    public static function jwtDecode($field = null): array|string|null
    {
        $payload = JWTAuth::parseToken()->getPayload()->toArray();

        if ($field) {
            $value = $payload[$field] ?? null;

            // যদি model field হয়, তবে শুধু class এর নাম return কর
            // if ($field === 'model' && $value) {
            //     return class_basename($value); // App\Models\HotelManagement\Hotel → Hotel
            // }

            return $value;
        }

        return $payload;
    }
}

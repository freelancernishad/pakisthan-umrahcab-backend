<?php

namespace App\Services\Login;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class GoogleAuthService
{
    public function login(Request $request)
    {
        try {
            // Fetch user data from Google API
            $response = Http::get('https://www.googleapis.com/oauth2/v3/userinfo', [
                'access_token' => $request->access_token,
            ]);

            if ($response->failed() || !isset($response['email'])) {
                return response()->json([
                    'error' => 'Invalid access token or Google API error.',
                ], 400);
            }

            $userData = $response->json();
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                // Register the user if they don't exist
                $user = User::create([
                    'name' => $userData['name'] ?? explode('@', $userData['email'])[0],
                    'email' => $userData['email'],
                    'password' => Hash::make(Str::random(16)), // Random password
                    'email_verified_at' => now(),
                ]);
            } else {
                $user->update(['email_verified_at'=> now()]);
            }

            // Authenticate the user
            Auth::login($user);

            // Payload for JWT (can be used for additional claims if needed)
            $userPayload = [
                'email' => $user->email,
                'name' => $user->name,
                'category' => $user->category ?? 'default',
                'email_verified' => $user->hasVerifiedEmail(),
            ];

            try {
                $token = JWTAuth::fromUser($user, ['guard' => 'user']);
            } catch (JWTException $e) {
                return response()->json([
                    'error' => 'Could not create JWT token',
                ], 500);
            }

            $secure = config('session.secure') ?? request()->secure();
            $domain = config('session.domain');
            $sameSite = config('session.same_site', 'lax');

            $cookie = cookie('token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
            $userCookie = cookie('user_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $userPayload,
            ], 200)
            ->withCookie($cookie)
            ->withCookie($userCookie);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during Google authentication.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}

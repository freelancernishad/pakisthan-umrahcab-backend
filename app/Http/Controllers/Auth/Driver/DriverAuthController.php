<?php

namespace App\Http\Controllers\Auth\Driver;

use App\Models\UmrahCab\UcDriver;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class DriverAuthController extends Controller
{
    /**
     * Log in a driver.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        $driver = UcDriver::where('username', $request->username)->first();

        if (!$driver) {
            logUserActivity('Driver Login Failed', 'Authentication', null, $request, false, [
                'username' => $request->username,
                'reason' => 'Driver username not found',
                'guard' => 'driver'
            ]);
            return response()->json(['message' => 'Driver username not found.'], 401);
        }

        if (Auth::guard('driver')->attempt($credentials)) {
            $driver = Auth::guard('driver')->user();

            $payload = [
                'id' => $driver->id,
                'name' => $driver->name,
                'username' => $driver->username,
                'phone' => $driver->phone,
                'license_no' => $driver->license_no,
                'vehicle_id' => $driver->vehicle_id,
                'edit_rights' => $driver->edit_rights,
                'guard' => 'driver'
            ];

            try {
                $token = JWTAuth::fromUser($driver, ['guard' => 'driver']);
            } catch (JWTException $e) {
                logUserActivity('Driver Login Failed', 'Authentication', null, $request, false, [
                    'driver_id' => $driver->id,
                    'username' => $driver->username,
                    'reason' => 'Could not create JWT token: ' . $e->getMessage(),
                    'guard' => 'driver'
                ]);
                return response()->json(['error' => 'Could not create token'], 500);
            }

            // Log successful login
            logUserActivity('Driver Login Successful', 'Authentication', null, $request, true, [
                'driver_id' => $driver->id,
                'username' => $driver->username,
                'guard' => 'driver'
            ]);

            $secure = config('session.secure') ?? request()->secure();
            $domain = config('session.domain');
            $sameSite = config('session.same_site', 'lax');

            $cookie = cookie('driver_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

            return response()->json([
                'token' => $token,
                'driver' => $payload,
            ], 200)
            ->withCookie($cookie);
        }

        // Log failed login due to incorrect password
        logUserActivity('Driver Login Failed', 'Authentication', null, $request, false, [
            'driver_id' => $driver->id,
            'username' => $driver->username,
            'reason' => 'Incorrect password',
            'guard' => 'driver'
        ]);

        return response()->json(['message' => 'Incorrect driver password.'], 401);
    }

    /**
     * Get the authenticated driver.
     */
    public function me(Request $request)
    {
        $driver = Auth::guard('driver')->user();
        if (!$driver) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        // Include vehicle if loaded
        $driver->load('vehicle');
        return response()->json($driver);
    }

    /**
     * Log out the authenticated driver.
     */
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not provided.'
                ], 401)
                ->withoutCookie('driver_token');
            }

            JWTAuth::invalidate($token);

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.'
            ], 200)
            ->withoutCookie('driver_token');
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout: ' . $e->getMessage()
            ], 500)
            ->withoutCookie('driver_token');
        }
    }

    /**
     * Check if a JWT token is valid.
     */
    public function checkToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided.'], 400);
        }

        try {
            $driver = JWTAuth::setToken($token)->authenticate();

            if (!$driver) {
                return response()->json(['message' => 'Token is invalid or driver not found.'], 401);
            }

            return response()->json(["message" => "Token is valid"], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired.'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Token is invalid.'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token is missing or invalid.'], 401);
        }
    }
}

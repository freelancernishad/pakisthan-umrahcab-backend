<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Models\Admin;

use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\OtpNotification;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\Auth\Admin\AdminRegisterRequest;
use App\Http\Requests\Auth\Admin\AdminLoginRequest;
use App\Http\Requests\Auth\Admin\AdminChangePasswordRequest;

class AdminAuthController extends Controller
{
 /**
     * Register a new admin with OTP/email verification.
     */
public function register(AdminRegisterRequest $request)
{

    $admin = Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Generate JWT token
    try {
        $token = JWTAuth::fromUser($admin, ['guard' => 'admin']);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Could not create token'], 500);
    }

    $emailSent = true;
    $emailMessage = '';

    if ($request->verify_url) {
        $verificationToken = Str::random(60);
        $admin->email_verification_hash = $verificationToken;
        $admin->save();

        try {
            Mail::to($admin->email)->send(new VerifyEmail($admin, $request->verify_url));
            $emailMessage = 'Registration successful. Verification email has been sent.';
        } catch (\Exception $e) {
            $emailSent = false;
            $emailMessage = 'Registration successful, but we could not send the verification email. Please contact support or try again later.';
            Log::error('Verification email failed: ' . $e->getMessage());
        }
    } else {
        $otp = random_int(100000, 999999);
        $admin->otp = Hash::make($otp);
        $admin->otp_expires_at = now()->addMinutes(5);
        $admin->save();

        try {
            Mail::to($admin->email)->send(new OtpNotification($otp));
            $emailMessage = 'Registration successful. OTP has been sent to your email.';
        } catch (\Exception $e) {
            $emailSent = false;
            $emailMessage = 'Registration successful, but we could not send the OTP. Please contact support or try again later.';
            Log::error('OTP email failed: ' . $e->getMessage());
        }
    }

    $secure = config('session.secure') ?? request()->secure();
    $domain = config('session.domain');
    $sameSite = config('session.same_site', 'lax');

    $cookie = cookie('admin_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

    return response()->json([
        'token' => $token,
        'admin' => [
            'email' => $admin->email,
            'name' => $admin->name,
            'username' => $admin->username,
            'role' => $admin->role,
            'permissions' => $admin->permissions,
            'email_verified' => !is_null($admin->email_verified_at),
        ],
        'message' => $emailMessage,
    ], 201)
    ->withCookie($cookie);
}

    /**
     * Log in an admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            logUserActivity('Admin Login Failed', 'Authentication', null, $request, false, [
                'email' => $request->email,
                'reason' => 'Admin account not found',
                'guard' => 'admin'
            ]);
            return response()->json(['message' => 'Admin account not found.'], 401);
        }

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            // Custom payload data
            $payload = [
                'email' => $admin->email,
                'name' => $admin->name,
                'username' => $admin->username,
                'role' => $admin->role,
                'permissions' => $admin->permissions,
                'email_verified' => !is_null($admin->email_verified_at),
            ];

            try {
                // Generate a JWT token with custom claims
                $token = JWTAuth::fromUser($admin, ['guard' => 'admin']);
            } catch (JWTException $e) {
                logUserActivity('Admin Login Failed', 'Authentication', $admin->id, $request, false, [
                    'email' => $admin->email,
                    'reason' => 'Could not create JWT token: ' . $e->getMessage(),
                    'guard' => 'admin'
                ]);
                return response()->json(['error' => 'Could not create token'], 500);
            }

            // Log successful login
            logUserActivity('Admin Login Successful', 'Authentication', null, $request, true, [
                'admin_id' => $admin->id,
                'email' => $admin->email,
                'guard' => 'admin'
            ]);

            $secure = config('session.secure') ?? request()->secure();
            $domain = config('session.domain');
            $sameSite = config('session.same_site', 'lax');

            $cookie = cookie('admin_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

            return response()->json([
                'token' => $token,
                'admin' => $payload,
            ], 200)
            ->withCookie($cookie);
        }

        // Log failed login due to incorrect password
        logUserActivity('Admin Login Failed', 'Authentication', null, $request, false, [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'reason' => 'Incorrect password',
            'guard' => 'admin'
        ]);

        return response()->json(['message' => 'Incorrect password credentials.'], 401);
    }

    /**
     * Get the authenticated admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json(Auth::guard('admin')->user());
    }

    /**
     * Log out the authenticated admin.
     *
     * @return \Illuminate\Http\JsonResponse
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
            ->withoutCookie('admin_token');
        }

        JWTAuth::invalidate($token);

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ], 200)
        ->withoutCookie('admin_token');
    } catch (JWTException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to logout, token invalidation failed: ' . $e->getMessage()
        ], 500)
        ->withoutCookie('admin_token');
    }
}
     /**
     * Change the password of the authenticated admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(AdminChangePasswordRequest $request)
    {

        // Get the currently authenticated admin
        $admin = Auth::guard('admin')->user();

        // Ensure $admin is an Eloquent model instance
        if (!$admin instanceof \App\Models\Admin) {
            $admin = \App\Models\Admin::find($admin->id);
        }

        // Check if the current password matches
        if (!Hash::check($request->current_password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 400);
        }

        // Update the password
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.'
        ], 200);
    }




    /**
     * Check if a JWT token is valid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkToken(Request $request)
    {
        $token = $request->bearerToken(); // Get the token from the Authorization header

        if (!$token) {
            return response()->json(['message' => 'Token not provided.'], 400);
        }

        try {
            $admin = JWTAuth::setToken($token)->authenticate();

            if (!$admin) {
                return response()->json(['message' => 'Token is invalid or admin not found.'], 401);
            }

            return response()->json(["message"=>"Token is valid"], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired.'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Token is invalid.'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token is missing or invalid.'], 401);
        }
    }

}

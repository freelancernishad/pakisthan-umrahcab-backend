<?php

namespace App\Http\Controllers\Auth\User;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;

use App\Mail\OtpNotification;
use Tymon\JWTAuth\Facades\JWTAuth;


use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\Login\AppleAuthService;
use App\Http\Resources\LoginRegisterUserResource;
use App\Services\Login\GoogleAuthService;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;

class AuthUserController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
public function register(RegisterRequest $request)
{

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'last_login_at' => now(),
        'is_blocked' => false,
        'is_active' => true,
        'role' => 'user',
    ]);
    // Log user registration
    logUserActivity(
        activity: 'User Registration',
        category: 'Authentication',
        userId: $user->id,
        request: $request,
        isSuccess: true,
        extraDetails: ['method' => 'register endpoint']
    );

    // Generate a JWT token
    try {
        $token = JWTAuth::fromUser($user, ['guard' => 'user']);
    } catch (JWTException $e) {
        logUserActivity(
            activity: 'JWT Token Generation Failed',
            category: 'Authentication',
            userId: $user->id,
            request: $request,
            isSuccess: false,
            extraDetails: ['error' => $e->getMessage()]
        );
        return response()->json(['error' => 'Could not create token'], 500);
    }

    // Email verification handling
    $verify_url = $request->verify_url ?? null;
    $emailSent = true;
    $emailMessage = '';

    if ($verify_url) {
        try {
            Mail::to($user->email)->send(new VerifyEmail($user, $verify_url));
            $emailMessage = 'Registration successful. Verification email has been sent.';
        } catch (\Exception $e) {
            $emailSent = false;
            $emailMessage = 'Registration successful, but we could not send the verification email. Please try again later.';
            Log::error('User verification email failed: ' . $e->getMessage());
            logUserActivity(
                activity: 'Email Verification Failed',
                category: 'Email',
                userId: $user->id,
                request: $request,
                isSuccess: false,
                extraDetails: ['error' => $e->getMessage()]
            );
        }
    } else {
        $otpResult = $this->sendOtp($user, $request, 'Registration');
        $emailSent = $otpResult['emailSent'];
        $emailMessage = $otpResult['emailMessage'];
    }

    $secure = config('session.secure') ?? request()->secure();
    $domain = config('session.domain');
    $sameSite = config('session.same_site', 'lax');

    $cookie = cookie('token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
    $userCookie = cookie('user_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

    return response()->json(new LoginRegisterUserResource($user, $token, $emailMessage, !$emailSent), 201)
        ->withCookie($cookie)
        ->withCookie($userCookie);
}



    /**
     * Log in a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
public function login(LoginRequest $request)
{
    // Handle Google login
    if ($request->access_token) {
        $googleAuthService = new GoogleAuthService();
        return $googleAuthService->login($request);
    }

    // Handle Apple login
    if ($request->identity_token) {
        $appleAuthService = new AppleAuthService();
        return $appleAuthService->login($request);
    }

    $credentials = $request->only('email', 'password');
    $user = User::where('email', $request->email)->first();

    // Check if user exists
    if (!$user) {
        logUserActivity('Login Failed', 'Authentication', null, $request, false, [
            'email' => $request->email,
            'reason' => 'User account not found'
        ]);
        return response()->json(['message' => 'User account not found.'], 401);
    }

    // Check if user is blocked
    if ($user->is_blocked) {
        logUserActivity('Login Blocked', 'Authentication', $user->id, $request, false, [
            'reason' => 'User is blocked'
        ]);

        return response()->json(['message' => 'Your account is blocked. Contact support.'], 403);
    }

    // Check if user is active
    if (!$user->is_active) {
        logUserActivity('Login Inactive', 'Authentication', $user->id, $request, false, [
            'reason' => 'User account is inactive'
        ]);

        return response()->json(['message' => 'Your account is not active.'], 403);
    }

    // Attempt authentication
    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            $otpResult = $this->sendOtp($user, $request, 'Login');

            logUserActivity('Login Failed - Unverified', 'Authentication', $user->id, $request, false, [
                'reason' => 'Email not verified, OTP sent'
            ]);

            return response()->json([
                'message' => 'Email is not verified. ' . $otpResult['emailMessage'],
                'emailSent' => $otpResult['emailSent']
            ], 403);
        }

       $user->last_login_at = now();
        $user->save();

        try {
            $token = JWTAuth::fromUser($user, ['guard' => 'user']);
        } catch (JWTException $e) {
            logUserActivity('Login Token Failed', 'Authentication', $user->id, $request, false, [
                'reason' => 'JWT token generation failed',
            ]);

            return response()->json(['error' => 'Could not create token'], 500);
        }

        // Log successful login
        logUserActivity('Login Successful', 'Authentication', $user->id, $request, true);



        $secure = config('session.secure') ?? request()->secure();
        $domain = config('session.domain');
        $sameSite = config('session.same_site', 'lax');

        $cookie = cookie('token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
        $userCookie = cookie('user_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

        return response()->json(new LoginRegisterUserResource($user, $token), 200)
            ->withCookie($cookie)
            ->withCookie($userCookie);
    }

    // Log failed login
    logUserActivity('Login Failed', 'Authentication', $user->id, $request, false, [
        'reason' => 'Invalid credentials'
    ]);

    return response()->json(['message' => 'Invalid credentials'], 401);
}



    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = Auth::user();
        $user->load(['planSubscriptions' => function ($query) {
            $query->where('status', 'active')->latest('start_date')->limit(1)->with('plan');
        }]);
        
        $user->active_plan = $user->planSubscriptions->first();
        unset($user->planSubscriptions); // Clean up response

        return response()->json($user);
    }

    /**
     * Log out the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                logUserActivity(
                    'Logout Attempt Without Token',
                    'Authentication',
                Auth::id() ?? null,
                    $request,
                    false,
                    ['reason' => 'Token not provided']
                );

                return response()->json([
                    'success' => false,
                    'message' => 'Token not provided.'
                ], 401)
                ->withoutCookie('token')
                ->withoutCookie('user_token');
            }



            logUserActivity(
            'User Logout',
                'Authentication',
                Auth::id(),
                $request,
                true,
                ['token' => $token]
            );
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.'
            ], 200)
            ->withoutCookie('token')
            ->withoutCookie('user_token');
        } catch (JWTException $e) {
            logUserActivity(
                activity: 'Logout Failed',
                category: 'Authentication',
                userId: Auth::id() ?? null,
                request: $request,
                isSuccess: false,
                extraDetails: ['error' => $e->getMessage()]
            );

            return response()->json([
                'success' => false,
                'message' => 'Failed to logout, token invalidation failed: ' . $e->getMessage()
            ], 500)
            ->withoutCookie('token')
            ->withoutCookie('user_token');
        }
    }


  /**
     * Change the password of the authenticated user.
     */
  public function changePassword(ChangePasswordRequest $request)
{

    $authUser = Auth::user();
    $user = User::find($authUser->id);

    // Check if the current password matches
    if (!Hash::check($request->current_password, $user->password)) {
        logUserActivity(
            activity: 'Password Change Failed (Wrong Current Password)',
            category: 'Account',
            userId: $user->id,
            request: $request,
            isSuccess: false,
            extraDetails: ['reason' => 'Current password mismatch']
        );

        return response()->json(['message' => 'Current password is incorrect.'], 400);
    }

    // Update the password
    $user->password = Hash::make($request->new_password);
    $user->save();

    logUserActivity(
        activity: 'Password Changed Successfully',
        category: 'Account',
        userId: $user->id,
        request: $request,
        isSuccess: true
    );

    return response()->json(['message' => 'Password updated successfully.']);
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
            // Authenticate the token and retrieve the authenticated user
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Token is invalid or user not found.'], 401);
            }

            $payload = [
                'email' => $user->email,
                'name' => $user->name,
                'email_verified' => $user->hasVerifiedEmail(), // Checks verification status
            ];

            return response()->json(['message' => 'Token is valid.','user'=>$payload], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired.'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Token is invalid.'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token is missing or malformed.'], 401);
        }
    }

    /**
     * Helper function to generate and send OTP.
     *
     * @param User $user
     * @param Request $request
     * @param string $context
     * @return array
     */
    private function sendOtp(User $user, Request $request, $context = 'Registration')
    {
        $otp = random_int(100000, 999999);
        $user->otp = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        $emailSent = true;
        
        $contextMessage = $context === 'Registration' ? 'Registration successful.' : 'Login attempt.';

        try {
            Mail::to($user->email)->send(new OtpNotification($otp));
            $emailMessage = $contextMessage . ' OTP has been sent to your email.';
        } catch (\Exception $e) {
            $emailSent = false;
            $emailMessage = $contextMessage . ' We could not send the OTP. Please try again later.';
            Log::error('User OTP email failed: ' . $e->getMessage());
            logUserActivity(
                activity: 'OTP Email Failed',
                category: 'Email',
                userId: $user->id,
                request: $request,
                isSuccess: false,
                extraDetails: ['error' => $e->getMessage()]
            );
        }

        return [
            'emailSent' => $emailSent,
            'emailMessage' => $emailMessage
        ];
    }
}

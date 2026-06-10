<?php

namespace App\Http\Controllers\Auth\Company;

use App\Models\UmrahCab\UcCompany;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class CompanyAuthController extends Controller
{
    /**
     * Log in a B2B company agent.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_username' => 'required|string',
            'agent_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = [
            'agent_username' => $request->agent_username,
            'password' => $request->agent_password,
        ];

        if (Auth::guard('company')->attempt($credentials)) {
            $company = Auth::guard('company')->user();

            $payload = [
                'id' => $company->id,
                'name' => $company->name,
                'agent_username' => $company->agent_username,
                'email' => $company->email,
                'phone' => $company->phone,
                'guard' => 'company'
            ];

            try {
                $token = JWTAuth::fromUser($company, ['guard' => 'company']);
            } catch (JWTException $e) {
                return response()->json(['error' => 'Could not create token'], 500);
            }

            $secure = config('session.secure') ?? request()->secure();
            $domain = config('session.domain');
            $sameSite = config('session.same_site', 'lax');

            $cookie = cookie('company_token', $token, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);

            return response()->json([
                'token' => $token,
                'company' => $payload,
            ], 200)
            ->withCookie($cookie);
        }

        return response()->json(['message' => 'Invalid B2B agent credentials.'], 401);
    }

    /**
     * Get the authenticated company agent.
     */
    public function me(Request $request)
    {
        $company = Auth::guard('company')->user();
        if (!$company) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return response()->json($company);
    }

    /**
     * Log out the authenticated company agent.
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
                ->withoutCookie('company_token');
            }

            JWTAuth::invalidate($token);

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.'
            ], 200)
            ->withoutCookie('company_token');
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout: ' . $e->getMessage()
            ], 500)
            ->withoutCookie('company_token');
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
            $company = JWTAuth::setToken($token)->authenticate();

            if (!$company) {
                return response()->json(['message' => 'Token is invalid or company not found.'], 401);
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

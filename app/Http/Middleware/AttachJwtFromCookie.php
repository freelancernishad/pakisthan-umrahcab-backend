<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AttachJwtFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = null;

        if ($request->is('user') || $request->is('user/*') || $request->is('api/user') || $request->is('api/user/*') || $request->is('api/auth/user/*')) {
             $token = $request->cookie('token') ?? $request->cookie('user_token') ?? $_COOKIE['token'] ?? $_COOKIE['user_token'] ?? null;
        } elseif ($request->is('admin') || $request->is('admin/*') || $request->is('api/admin') || $request->is('api/admin/*') || $request->is('api/auth/admin/*')) {
             $token = $request->cookie('admin_token') ?? $_COOKIE['admin_token'] ?? null;
        } elseif ($request->is('company') || $request->is('company/*') || $request->is('api/company') || $request->is('api/company/*') || $request->is('api/auth/company/*') || $request->is('api/umrahcab/company/*')) {
             $token = $request->cookie('company_token') ?? $_COOKIE['company_token'] ?? null;
        } elseif ($request->is('driver') || $request->is('driver/*') || $request->is('api/driver') || $request->is('api/driver/*') || $request->is('api/auth/driver/*') || $request->is('api/umrahcab/driver/*')) {
             $token = $request->cookie('driver_token') ?? $_COOKIE['driver_token'] ?? null;
        }

        // Fallback or generic logic if needed
        if (!$token) {
             $token = $request->cookie('admin_token') ?? $_COOKIE['admin_token'] ?? $request->cookie('company_token') ?? $_COOKIE['company_token'] ?? $request->cookie('driver_token') ?? $_COOKIE['driver_token'] ?? $request->cookie('token') ?? $request->cookie('user_token') ?? $_COOKIE['token'] ?? $_COOKIE['user_token'] ?? null;
        }

        if ($token === 'null' || $token === 'undefined') {
            $token = null;
        }

        if ($token && !$request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        // Auto-refresh token if expired
        if ($token) {
            try {
                \Tymon\JWTAuth\Facades\JWTAuth::setToken($token)->check();
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                try {
                    $newToken = \Tymon\JWTAuth\Facades\JWTAuth::refresh($token);
                    $request->headers->set('Authorization', 'Bearer ' . $newToken);
                    
                    // Determine which cookie name to use
                    if ($request->is('admin') || $request->is('admin/*') || $request->is('api/admin') || $request->is('api/admin/*') || $request->is('api/auth/admin/*')) {
                        $cookieName = 'admin_token';
                    } elseif ($request->is('company') || $request->is('company/*') || $request->is('api/company') || $request->is('api/company/*') || $request->is('api/auth/company/*') || $request->is('api/umrahcab/company/*')) {
                        $cookieName = 'company_token';
                    } elseif ($request->is('driver') || $request->is('driver/*') || $request->is('api/driver') || $request->is('api/driver/*') || $request->is('api/auth/driver/*') || $request->is('api/umrahcab/driver/*')) {
                        $cookieName = 'driver_token';
                    } else {
                        $cookieName = 'token';
                    }
                    
                    $secure = config('session.secure') ?? $request->secure();
                    $domain = config('session.domain');
                    $sameSite = config('session.same_site', 'lax');

                    \Illuminate\Support\Facades\Cookie::queue($cookieName, $newToken, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
                    if ($cookieName === 'token') {
                        \Illuminate\Support\Facades\Cookie::queue('user_token', $newToken, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
                    }
                } catch (\Tymon\JWTAuth\Exceptions\JWTException $refreshException) {
                    // Refresh token also expired or blacklisted, let downstream handle the 401
                }
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                // Other JWT validation failures, let downstream handle
            }
        }

        return $next($request);
    }
}

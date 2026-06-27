<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

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
        $cookieName = $this->getCookieNameForPath($request);
        
        // Try reading the route-specific token first
        $token = $request->cookie($cookieName) ?? $_COOKIE[$cookieName] ?? null;

        // Special handling for legacy/standard user token naming
        if ($cookieName === 'token' && !$token) {
            $token = $request->cookie('user_token') ?? $_COOKIE['user_token'] ?? null;
        }

        // Fallback: if route-specific token is missing, attempt other tokens
        if (!$token) {
            $token = $request->cookie('admin_token') ?? $_COOKIE['admin_token'] 
                ?? $request->cookie('company_token') ?? $_COOKIE['company_token'] 
                ?? $request->cookie('driver_token') ?? $_COOKIE['driver_token'] 
                ?? $request->cookie('token') ?? $_COOKIE['token'] 
                ?? $request->cookie('user_token') ?? $_COOKIE['user_token'] 
                ?? null;
        }

        if ($token === 'null' || $token === 'undefined') {
            $token = null;
        }

        if ($token && !$request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        Log::info('AttachJwtFromCookie execution', [
            'url' => $request->fullUrl(),
            'cookies' => $request->cookies->all(),
            'token_from_cookie' => $token ? substr($token, 0, 15) . '...' : null,
            'authorization_header' => $request->header('Authorization') ? substr($request->header('Authorization'), 0, 22) . '...' : null
        ]);

        // Auto-refresh token if expired
        if ($token) {
            try {
                JWTAuth::setToken($token)->check();
            } catch (TokenExpiredException $e) {
                try {
                    $newToken = JWTAuth::refresh($token);
                    $request->headers->set('Authorization', 'Bearer ' . $newToken);
                    
                    $secure = config('session.secure') ?? $request->secure();
                    $domain = config('session.domain');
                    $sameSite = config('session.same_site', 'lax');

                    Cookie::queue($cookieName, $newToken, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
                    if ($cookieName === 'token') {
                        Cookie::queue('user_token', $newToken, config('jwt.ttl', 43200), '/', $domain, $secure, true, false, $sameSite);
                    }
                } catch (JWTException $refreshException) {
                    // Refresh token also expired or blacklisted, let downstream handle the 401
                }
            } catch (JWTException $e) {
                // Other JWT validation failures, let downstream handle
            }
        }

        return $next($request);
    }

    /**
     * Determine the correct token cookie name based on the request path.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function getCookieNameForPath(Request $request): string
    {
        if ($request->is('user') || $request->is('user/*') || $request->is('api/user') || $request->is('api/user/*') || $request->is('api/auth/user/*')) {
            return 'token';
        }

        if ($request->is('admin') || $request->is('admin/*') || $request->is('api/admin') || $request->is('api/admin/*') || $request->is('api/auth/admin/*') || $request->is('api/umrahcab/admin/*') || $request->is('api/umrahcab/admin') || $request->is('api/umrahcab/chat/admin/*') || $request->is('api/umrahcab/chat/admin')) {
            return 'admin_token';
        }

        if ($request->is('company') || $request->is('company/*') || $request->is('api/company') || $request->is('api/company/*') || $request->is('api/auth/company/*') || $request->is('api/umrahcab/company/*') || $request->is('api/umrahcab/company-panel/*') || $request->is('api/umrahcab/company-panel')) {
            return 'company_token';
        }

        if ($request->is('driver') || $request->is('driver/*') || $request->is('api/driver') || $request->is('api/driver/*') || $request->is('api/auth/driver/*') || $request->is('api/umrahcab/driver/*') || $request->is('api/umrahcab/driver-panel/*') || $request->is('api/umrahcab/driver-panel')) {
            return 'driver_token';
        }

        return 'token';
    }
}

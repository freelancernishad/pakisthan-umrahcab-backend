<?php

namespace App\Http\Middleware;

use App\Models\AllowedOrigin;
use Closure;

class WhitelistOriginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $origin = $request->header('Origin');
        $allowedOrigins = [
            'http://localhost:3000', 
            'http://localhost:3001', 
            'http://127.0.0.1:3000', 
            'http://127.0.0.1:3001'
        ];

        // Handle OPTIONS request (preflight)
        if ($request->isMethod('OPTIONS')) {
            return $next($request);
        }

        // Allow if no origin (e.g. server-to-server or direct hit)
        if (!$origin) {
            return $next($request);
        }

        // Allow same-origin requests (e.g., local admin panel on port 8000)
        if ($origin === $request->getSchemeAndHttpHost()) {
            return $next($request);
        }

        // Allow localhost and 127.0.0.1 on any port for dev
        if (preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?$#i', $origin)) {
            return $next($request);
        }

        // Check whitelisted list
        if (in_array($origin, $allowedOrigins)) {
            return $next($request);
        }

        // Check if all origins or specific origin allowed in DB (cached)
        $isAllowed = \Illuminate\Support\Facades\Cache::remember('allowed_origin_' . md5($origin), 3600, function () use ($origin) {
            return AllowedOrigin::where('origin_url', '*')->orWhere('origin_url', $origin)->exists();
        });

        if ($isAllowed) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access denied. Your origin is not allowed.',
            'origin' => $origin,
        ], 403);
    }
}

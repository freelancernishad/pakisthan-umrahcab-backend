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

        // Check whitelisted list
        if (in_array($origin, $allowedOrigins)) {
            return $next($request);
        }

        // Check if all origins allowed in DB
        $allowedAll = AllowedOrigin::where('origin_url', '*')->exists();
        if ($allowedAll) {
            return $next($request);
        }

        // Check specific origin in DB
        $allowedDbOrigin = AllowedOrigin::where('origin_url', $origin)->exists();
        if ($allowedDbOrigin) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access denied. Your origin is not allowed.',
            'origin' => $origin,
        ], 403);
    }
}

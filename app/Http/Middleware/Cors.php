<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
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
        $origin = $request->header('Origin') ?: '*';

        // Allowed origins for development
        $allowedOrigins = [
            'http://localhost:3000',
            'http://localhost:3001',
            'http://127.0.0.1:3000',
            'http://127.0.0.1:3001',
        ];

        // Specific allowed origin if in list, otherwise '*' (or keep incoming if it's whitelisted in DB later)
        if (in_array($origin, $allowedOrigins)) {
            $currentOrigin = $origin;
        } else {
            $currentOrigin = $origin; // Default to incoming for now to bypass browser checks
        }

        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            return response()->json('OK', 200)
                ->header('Access-Control-Allow-Origin', $currentOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        $response = $next($request);

        // Ensure headers are on EVERY response (Success, 401, 403, 500, etc.)
        if (method_exists($response, 'header')) {
            $response->header('Access-Control-Allow-Origin', $currentOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}

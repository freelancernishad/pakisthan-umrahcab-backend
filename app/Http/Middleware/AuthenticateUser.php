<?php

namespace App\Http\Middleware;

// use App\Models\TokenBlacklist; // Import your TokenBlacklist model
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
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
        // Get the Bearer token from the Authorization header
        $token = $request->bearerToken();

        // Check if the token is blacklisted
        // if ($token && TokenBlacklist::where('token', $token)->exists()) {
        //     return response()->json([], 401);
        // }

        // Check if the user is authenticated
        if (!Auth::guard('user')->check()) {
            return response()->json([], 401);
        }

        $user = Auth::guard('user')->user();

        if ($user->is_blocked) {
            return response()->json(['message' => 'Your account is blocked. Contact support.'], 403);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Your account is not active.'], 403);
        }

        return $next($request);
    }
}

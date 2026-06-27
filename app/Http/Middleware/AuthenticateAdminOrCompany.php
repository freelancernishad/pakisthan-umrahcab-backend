<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdminOrCompany
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
        \Illuminate\Support\Facades\Log::info('AuthenticateAdminOrCompany check', [
            'url' => $request->fullUrl(),
            'authorization_header' => $request->header('Authorization') ? substr($request->header('Authorization'), 0, 22) . '...' : null,
            'admin_check' => Auth::guard('admin')->check(),
            'company_check' => Auth::guard('company')->check(),
        ]);

        // Check if the user is authenticated as either admin or company
        if (!Auth::guard('admin')->check() && !Auth::guard('company')->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('admin.login.view');
        }

        return $next($request);
    }
}

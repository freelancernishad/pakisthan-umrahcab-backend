<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $module The module name (e.g., 'bookings', 'drivers')
     * @param  string  $action The required action ('view', 'edit', 'delete')
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $module, string $action = 'view')
    {
        $admin = Auth::guard('admin')->user();

        // If not authenticated as admin, let downstream handle it or abort
        if (!$admin) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Super Admins bypass all permission checks
        if ($admin->role === 'SUPER_ADMIN') {
            return $next($request);
        }

        // Sub-Admins must have the permission matrix defined
        $permissions = $admin->permissions;

        if (!$permissions || !isset($permissions[$module])) {
            return response()->json([
                'success' => false,
                'message' => "You do not have permission to access the '{$module}' module."
            ], 403);
        }

        $userRight = $permissions[$module]; // 'none', 'view', 'edit', 'delete'

        // Determine if the user's right is sufficient for the requested action
        $hasAccess = false;

        if ($action === 'view') {
            $hasAccess = in_array($userRight, ['view', 'edit', 'delete']);
        } elseif ($action === 'edit') {
            $hasAccess = in_array($userRight, ['edit', 'delete']);
        } elseif ($action === 'delete') {
            $hasAccess = ($userRight === 'delete');
        }

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => "You do not have permission to perform this action ('{$action}' on '{$module}')."
            ], 403);
        }

        return $next($request);
    }
}

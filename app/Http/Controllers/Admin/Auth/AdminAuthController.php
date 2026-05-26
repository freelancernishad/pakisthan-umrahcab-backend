<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Check for session-based auth
        if (Auth::guard('admin_web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        // Check for JWT-based auth via cookie
        if ($request->hasCookie('admin_token')) {
            try {
                // Manually set the token and check
                $token = $request->cookie('admin_token');
                if ($token && Auth::guard('admin')->setToken($token)->check()) {
                    return redirect()->route('admin.dashboard');
                }
            } catch (\Exception $e) {
                // Token invalid, proceed to login form
            }
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin_web')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin_web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

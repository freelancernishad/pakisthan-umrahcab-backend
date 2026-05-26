<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\Admin\AdminSendResetLinkRequest;
use App\Http\Requests\Auth\Admin\AdminResetPasswordRequest;

class AdminPasswordResetController extends Controller
{
    /**
     * Send a password reset link to the admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(AdminSendResetLinkRequest $request)
    {
        $email = $request->input('email');
        $resetUrlBase = $request->input('redirect_url');

        // Find the admin by email
        $admin = Admin::where('email', $email)->first();

        // Send the password reset link
        $response = Password::broker('admins')->sendResetLink(
            $request->only('email'),
            function ($admin, $token) use ($resetUrlBase) {
                $resetUrl = "{$resetUrlBase}?token={$token}&email={$admin->email}";

                Mail::to($admin->email)->send(new PasswordResetMail($admin, $resetUrl));
            }
        );

        if ($response == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => __($response),
                'admin' => [
                    'name' => $admin->name,
                    'email' => $admin->email
                ]
            ], 200);
        } else {
            return response()->json(['error' => __($response)], 400);
        }
    }

    /**
     * Reset the admin password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(AdminResetPasswordRequest $request)
    {

        $response = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->password = Hash::make($password);
                $admin->save();

                event(new PasswordReset($admin));
            }
        );

        return $response == Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password has been reset successfully.'])
            : response()->json(['error' => 'Unable to reset password.'], 500);
    }
}

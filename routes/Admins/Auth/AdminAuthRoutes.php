<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Auth\Admin\AdminAuthController;
use App\Http\Controllers\Auth\Admin\AdminVerificationController;
use App\Http\Controllers\Auth\Admin\AdminPasswordResetController;

Route::prefix('auth/admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('register', [AdminAuthController::class, 'register']);

    Route::middleware(AuthenticateAdmin::class)->group(function () { // Applying admin middleware
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('/change-password', [AdminAuthController::class, 'changePassword']);
        Route::get('check-token', [AdminAuthController::class, 'checkToken']);
    });
});

// Password reset routes
Route::post('admin/password/email', [AdminPasswordResetController::class, 'sendResetLinkEmail']);
Route::post('admin/password/reset', [AdminPasswordResetController::class, 'reset']);

Route::post('admin/verify-otp', [AdminVerificationController::class, 'verifyOtp']);
Route::post('admin/resend/otp', [AdminVerificationController::class, 'resendOtp']);
Route::get('admin/email/verify/{hash}', [AdminVerificationController::class, 'verifyEmail']);
Route::post('admin/resend/verification-link', [AdminVerificationController::class, 'resendVerificationLink']);

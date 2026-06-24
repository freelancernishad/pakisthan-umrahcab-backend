<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateDriver;
use App\Http\Controllers\Auth\Driver\DriverAuthController;

Route::prefix('auth/driver')->group(function () {
    Route::post('login', [DriverAuthController::class, 'login'])->name('driver.login');

    Route::middleware(AuthenticateDriver::class)->group(function () {
        Route::post('logout', [DriverAuthController::class, 'logout']);
        Route::get('me', [DriverAuthController::class, 'me']);
        Route::get('check-token', [DriverAuthController::class, 'checkToken']);
    });
});

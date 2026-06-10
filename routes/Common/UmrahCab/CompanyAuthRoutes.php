<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateCompany;
use App\Http\Controllers\Auth\Company\CompanyAuthController;

Route::prefix('auth/company')->group(function () {
    Route::post('login', [CompanyAuthController::class, 'login'])->name('company.login');

    Route::middleware(AuthenticateCompany::class)->group(function () {
        Route::post('logout', [CompanyAuthController::class, 'logout']);
        Route::get('me', [CompanyAuthController::class, 'me']);
        Route::get('check-token', [CompanyAuthController::class, 'checkToken']);
    });
});

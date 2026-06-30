<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcServiceController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\AuthenticateAdminOrCompany;

// Guest / Public read-only operations
Route::get('/services', [UcServiceController::class, 'index']);
Route::get('/services/{id}', [UcServiceController::class, 'show']);

// Write operations
Route::middleware([AttachJwtFromCookie::class])->group(function () {
    Route::middleware([AuthenticateAdminOrCompany::class])->group(function () {
        Route::post('/services', [UcServiceController::class, 'store']);
    });
    
    Route::middleware([AuthenticateAdmin::class])->group(function () {
        Route::put('/services/{id}', [UcServiceController::class, 'update']);
        Route::delete('/services/{id}', [UcServiceController::class, 'destroy']);
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcFlightController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\AuthenticateAdminOrCompany;

// Guest / Public read-only operations
Route::get('/flights', [UcFlightController::class, 'index']);
Route::get('/flights/{id}', [UcFlightController::class, 'show']);

// Write operations
Route::middleware([AttachJwtFromCookie::class])->group(function () {
    Route::middleware([AuthenticateAdminOrCompany::class])->group(function () {
        Route::post('/flights', [UcFlightController::class, 'store']);
    });
    
    Route::middleware([AuthenticateAdmin::class])->group(function () {
        Route::put('/flights/{id}', [UcFlightController::class, 'update']);
        Route::delete('/flights/{id}', [UcFlightController::class, 'destroy']);
    });
});

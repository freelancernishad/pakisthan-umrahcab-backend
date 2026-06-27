<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcHotelController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\AuthenticateAdminOrCompany;

// Guest / Public read-only operations
Route::get('/hotels', [UcHotelController::class, 'index']);
Route::get('/hotels/{id}', [UcHotelController::class, 'show']);

// Write operations
Route::middleware([AttachJwtFromCookie::class])->group(function () {
    Route::middleware([AuthenticateAdminOrCompany::class])->group(function () {
        Route::post('/hotels', [UcHotelController::class, 'store']);
    });
    
    Route::middleware([AuthenticateAdmin::class])->group(function () {
        Route::put('/hotels/{id}', [UcHotelController::class, 'update']);
        Route::delete('/hotels/{id}', [UcHotelController::class, 'destroy']);
    });
});

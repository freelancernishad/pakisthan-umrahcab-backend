<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcHotelController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;

// Guest / Public read-only operations
Route::get('/hotels', [UcHotelController::class, 'index']);
Route::get('/hotels/{id}', [UcHotelController::class, 'show']);

// Admin-only write operations
Route::middleware([AttachJwtFromCookie::class, AuthenticateAdmin::class])->group(function () {
    Route::post('/hotels', [UcHotelController::class, 'store']);
    Route::put('/hotels/{id}', [UcHotelController::class, 'update']);
    Route::delete('/hotels/{id}', [UcHotelController::class, 'destroy']);
});

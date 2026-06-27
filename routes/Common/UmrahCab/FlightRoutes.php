<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcFlightController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;

// Guest / Public read-only operations
Route::get('/flights', [UcFlightController::class, 'index']);
Route::get('/flights/{id}', [UcFlightController::class, 'show']);

// Admin-only write operations
Route::middleware([AttachJwtFromCookie::class, AuthenticateAdmin::class])->group(function () {
    Route::post('/flights', [UcFlightController::class, 'store']);
    Route::put('/flights/{id}', [UcFlightController::class, 'update']);
    Route::delete('/flights/{id}', [UcFlightController::class, 'destroy']);
});

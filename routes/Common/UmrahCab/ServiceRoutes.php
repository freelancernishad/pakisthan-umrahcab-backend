<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcServiceController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;

// Guest / Public read-only operations
Route::get('/services', [UcServiceController::class, 'index']);
Route::get('/services/{id}', [UcServiceController::class, 'show']);

// Admin-only write operations
Route::middleware([AttachJwtFromCookie::class, AuthenticateAdmin::class])->group(function () {
    Route::post('/services', [UcServiceController::class, 'store']);
    Route::put('/services/{id}', [UcServiceController::class, 'update']);
    Route::delete('/services/{id}', [UcServiceController::class, 'destroy']);
});

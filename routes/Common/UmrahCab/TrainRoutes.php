<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcTrainController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;

// Guest / Public read-only operations
Route::get('/trains', [UcTrainController::class, 'index']);
Route::get('/trains/{id}', [UcTrainController::class, 'show']);

// Admin-only write operations
Route::middleware([AttachJwtFromCookie::class, AuthenticateAdmin::class])->group(function () {
    Route::post('/trains', [UcTrainController::class, 'store']);
    Route::put('/trains/{id}', [UcTrainController::class, 'update']);
    Route::delete('/trains/{id}', [UcTrainController::class, 'destroy']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcTrainController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\AuthenticateAdminOrCompany;

// Guest / Public read-only operations
Route::get('/trains', [UcTrainController::class, 'index']);
Route::get('/trains/{id}', [UcTrainController::class, 'show']);

// Write operations
Route::middleware([AttachJwtFromCookie::class])->group(function () {
    Route::middleware([AuthenticateAdminOrCompany::class])->group(function () {
        Route::post('/trains', [UcTrainController::class, 'store']);
    });
    
    Route::middleware([AuthenticateAdmin::class])->group(function () {
        Route::put('/trains/{id}', [UcTrainController::class, 'update']);
        Route::delete('/trains/{id}', [UcTrainController::class, 'destroy']);
    });
});

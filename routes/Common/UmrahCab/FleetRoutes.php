<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcFleetController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdminOrCompany;
use App\Http\Middleware\AuthenticateAdmin;

Route::middleware([AttachJwtFromCookie::class])->group(function () {
    Route::middleware(AuthenticateAdminOrCompany::class)->group(function () {
        Route::get('/fleet', [UcFleetController::class, 'index']);
    });

    Route::middleware(AuthenticateAdmin::class)->group(function () {
        Route::post('/fleet', [UcFleetController::class, 'store']);
        Route::put('/fleet/{id}', [UcFleetController::class, 'update']);
        Route::delete('/fleet/{id}', [UcFleetController::class, 'destroy']);
    });
});

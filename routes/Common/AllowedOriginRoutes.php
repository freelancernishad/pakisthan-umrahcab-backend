<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Common\AllowedOrigin\AllowedOriginController;



Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {
        Route::get('/allowed-origins', [AllowedOriginController::class, 'index']);
        Route::post('/allowed-origins', [AllowedOriginController::class, 'store']);
        Route::put('/allowed-origins/{id}', [AllowedOriginController::class, 'update']);
        Route::delete('/allowed-origins/{id}', [AllowedOriginController::class, 'destroy']);
    });
});

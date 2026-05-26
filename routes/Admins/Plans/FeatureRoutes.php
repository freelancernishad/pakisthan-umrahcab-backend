<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Admin\Plans\FeatureController;

Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {
        Route::prefix('plan/features')->group(function () {
            Route::get('/', [FeatureController::class, 'index']);
            Route::post('/', [FeatureController::class, 'store']);
            Route::get('{id}', [FeatureController::class, 'show']);
            Route::put('{id}', [FeatureController::class, 'update']);
            Route::delete('{id}', [FeatureController::class, 'destroy']);
            Route::get('/template/list', [FeatureController::class, 'templateInputList']);
        });

    });
});

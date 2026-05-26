<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Admin\Coupon\CouponController;

Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {
        Route::prefix('coupons')->group(function () {
            Route::get('/', [CouponController::class, 'index']);
            Route::post('/', [CouponController::class, 'store']);
            Route::post('/{id}', [CouponController::class, 'update']);
            Route::delete('/{id}', [CouponController::class, 'destroy']);
        });


    });
});

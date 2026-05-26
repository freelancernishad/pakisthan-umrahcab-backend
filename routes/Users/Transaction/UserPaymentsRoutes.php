<?php


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\User\Plan\UserPlanController;


Route::prefix('/user')->group(function () {
    Route::middleware(AuthenticateUser::class)->group(function () { // Applying user middleware
        Route::get('/payments', [UserPlanController::class, 'getUserPayments']);

    });
});


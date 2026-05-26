<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Admin\Subscriptions\PlanSubscriptionsController;

Route::prefix('admin')->middleware(AuthenticateAdmin::class)->group(function () {

    Route::get('/subscriptions', [PlanSubscriptionsController::class, 'getAllSubscriptions']);
    Route::post('/subscriptions/{id}/cancel', [PlanSubscriptionsController::class, 'cancelSubscription']);
    Route::get('/payments', [PlanSubscriptionsController::class, 'getAllPayments']);
});

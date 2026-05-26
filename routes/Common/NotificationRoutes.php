<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Common\Notifications\NotificationController;
use App\Http\Controllers\Common\AllowedOrigin\AllowedOriginController;



Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {

        // Get notifications for the authenticated user or admin
        Route::get('/notifications', [NotificationController::class, 'index']);

        // Mark a notification as read
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);

        // Create a notification for a user (admin only)
        Route::post('/notifications/create-for-user', [NotificationController::class, 'createForUser']);
    });
});


Route::prefix('user')->group(function () {
    Route::middleware(AuthenticateUser::class)->group(function () {

        // Get notifications for the authenticated user or admin
        Route::get('/notifications', [NotificationController::class, 'index']);

        // Mark a notification as read
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);


    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcBookingController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;

// Guest / Public operations
Route::post('/bookings', [UcBookingController::class, 'store']);
Route::get('/bookings/status/{code}', [UcBookingController::class, 'getStatus']);

// Admin-only operations
Route::middleware([AttachJwtFromCookie::class, AuthenticateAdmin::class])->group(function () {
    Route::get('/bookings/upcoming-reminders', [UcBookingController::class, 'upcomingReminders']);
    Route::get('/bookings/summary', [UcBookingController::class, 'dashboardSummary']);
    Route::get('/bookings', [UcBookingController::class, 'index']);
    Route::get('/bookings/{id}', [UcBookingController::class, 'show']);
    Route::put('/bookings/{id}', [UcBookingController::class, 'update']);
});

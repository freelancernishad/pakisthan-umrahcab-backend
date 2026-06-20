<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcBookingController;

Route::get('/bookings/summary', [UcBookingController::class, 'dashboardSummary']);
Route::get('/bookings', [UcBookingController::class, 'index']);
Route::get('/bookings/{id}', [UcBookingController::class, 'show']);
Route::post('/bookings', [UcBookingController::class, 'store']);
Route::put('/bookings/{id}', [UcBookingController::class, 'update']);
Route::get('/bookings/status/{code}', [UcBookingController::class, 'getStatus']);

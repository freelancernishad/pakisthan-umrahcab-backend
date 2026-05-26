<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcFlightController;

Route::get('/flights', [UcFlightController::class, 'index']);
Route::post('/flights', [UcFlightController::class, 'store']);
Route::get('/flights/{id}', [UcFlightController::class, 'show']);
Route::put('/flights/{id}', [UcFlightController::class, 'update']);
Route::delete('/flights/{id}', [UcFlightController::class, 'destroy']);

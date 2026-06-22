<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcHotelController;

Route::get('/hotels', [UcHotelController::class, 'index']);
Route::post('/hotels', [UcHotelController::class, 'store']);
Route::get('/hotels/{id}', [UcHotelController::class, 'show']);
Route::put('/hotels/{id}', [UcHotelController::class, 'update']);
Route::delete('/hotels/{id}', [UcHotelController::class, 'destroy']);

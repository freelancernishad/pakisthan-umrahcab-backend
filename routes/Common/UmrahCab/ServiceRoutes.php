<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcServiceController;

Route::get('/services', [UcServiceController::class, 'index']);
Route::post('/services', [UcServiceController::class, 'store']);
Route::get('/services/{id}', [UcServiceController::class, 'show']);
Route::put('/services/{id}', [UcServiceController::class, 'update']);
Route::delete('/services/{id}', [UcServiceController::class, 'destroy']);

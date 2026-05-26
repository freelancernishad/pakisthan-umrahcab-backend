<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcTrainController;

Route::get('/trains', [UcTrainController::class, 'index']);
Route::post('/trains', [UcTrainController::class, 'store']);
Route::get('/trains/{id}', [UcTrainController::class, 'show']);
Route::put('/trains/{id}', [UcTrainController::class, 'update']);
Route::delete('/trains/{id}', [UcTrainController::class, 'destroy']);

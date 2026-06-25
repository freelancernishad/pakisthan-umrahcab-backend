<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcFleetController;

Route::get('/fleet', [UcFleetController::class, 'index']);
Route::post('/fleet', [UcFleetController::class, 'store']);
Route::put('/fleet/{id}', [UcFleetController::class, 'update']);
Route::delete('/fleet/{id}', [UcFleetController::class, 'destroy']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcFleetController;

Route::get('/fleet', [UcFleetController::class, 'index']);
Route::put('/fleet/{id}', [UcFleetController::class, 'update']);

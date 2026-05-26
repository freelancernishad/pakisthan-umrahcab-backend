<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcPerformanceController;

Route::get('/performance', [UcPerformanceController::class, 'index']);

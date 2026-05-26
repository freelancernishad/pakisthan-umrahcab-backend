<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcPaymentController;

Route::get('/payments', [UcPaymentController::class, 'index']);
Route::post('/payments', [UcPaymentController::class, 'store']);

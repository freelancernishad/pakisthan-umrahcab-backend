<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcCustomerController;

Route::get('/customers', [UcCustomerController::class, 'index']);
Route::post('/customers', [UcCustomerController::class, 'store']);
Route::put('/customers/{id}', [UcCustomerController::class, 'update']);
Route::get('/customers/{id}', [UcCustomerController::class, 'show']);

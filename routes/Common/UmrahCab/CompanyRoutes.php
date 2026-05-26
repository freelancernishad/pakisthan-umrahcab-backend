<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcCompanyController;

Route::get('/companies', [UcCompanyController::class, 'index']);
Route::post('/companies', [UcCompanyController::class, 'store']);

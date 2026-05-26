<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcUserController;

Route::get('/users', [UcUserController::class, 'index']);
Route::get('/users/{id}', [UcUserController::class, 'show']);
Route::post('/users', [UcUserController::class, 'store']);
Route::put('/users/{id}', [UcUserController::class, 'update']);
Route::delete('/users/{id}', [UcUserController::class, 'destroy']);

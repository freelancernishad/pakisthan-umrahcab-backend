<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcFollowupController;

Route::get('/followups', [UcFollowupController::class, 'index']);
Route::post('/followups', [UcFollowupController::class, 'store']);
Route::get('/followups/{id}', [UcFollowupController::class, 'show']);
Route::put('/followups/{id}', [UcFollowupController::class, 'update']);
Route::delete('/followups/{id}', [UcFollowupController::class, 'destroy']);

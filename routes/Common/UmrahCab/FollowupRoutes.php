<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcFollowupController;

Route::get('/followups', [UcFollowupController::class, 'index']);
Route::post('/followups', [UcFollowupController::class, 'store']);

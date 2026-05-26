<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcNoticeController;

Route::get('/notices', [UcNoticeController::class, 'index']);
Route::post('/notices', [UcNoticeController::class, 'store']);

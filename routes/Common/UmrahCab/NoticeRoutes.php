<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcNoticeController;
use App\Http\Middleware\AuthenticateAdminOrCompany;
use App\Http\Middleware\AuthenticateAdmin;

Route::middleware(AuthenticateAdminOrCompany::class)->group(function () {
    Route::get('/notices', [UcNoticeController::class, 'index']);
});

Route::middleware(AuthenticateAdmin::class)->group(function () {
    Route::post('/notices', [UcNoticeController::class, 'store']);
});

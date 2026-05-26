<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\NoticeController;
use App\Http\Middleware\AuthenticateAdmin;

// Public routes for fetching notices
Route::get('notices', [NoticeController::class, 'index']);
Route::get('notices/{notice}', [NoticeController::class, 'show']);

// Admin-only protected routes
Route::middleware([AuthenticateAdmin::class])->group(function () {
    Route::post('notices', [NoticeController::class, 'store']);
    Route::put('notices/{notice}', [NoticeController::class, 'update']);
    Route::delete('notices/{notice}', [NoticeController::class, 'destroy']);
});


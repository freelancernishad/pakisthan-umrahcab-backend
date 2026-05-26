<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Admin\UserManagement\AdminUserController;

Route::prefix('admin')->middleware(AuthenticateAdmin::class)->group(function () {
    Route::get('users/', [AdminUserController::class, 'index']);
    Route::get('user/{id}', [AdminUserController::class, 'show']);
    Route::patch('/user/{id}', [AdminUserController::class, 'update']);
    Route::patch('users/{id}/toggle-active', [AdminUserController::class, 'toggleActive']);
    Route::patch('users/{id}/toggle-block', [AdminUserController::class, 'toggleBlock']);
    Route::patch('users/{id}/reset-password', [AdminUserController::class, 'resetPassword']);
    Route::patch('users/{id}/verify-email', [AdminUserController::class, 'verifyEmail']);
    Route::patch('users/{id}/update-notes', [AdminUserController::class, 'updateNotes']);
    Route::post('users/bulk-action', [AdminUserController::class, 'bulkAction']);
    Route::post('users/{id}/impersonate', [AdminUserController::class, 'impersonate']);
    Route::delete('users/{id}', [AdminUserController::class, 'destroy']);
});

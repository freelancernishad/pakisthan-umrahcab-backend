<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Api\UmrahCab\UcSubAdminController;

Route::prefix('admin')->middleware(AuthenticateAdmin::class)->group(function () {
    Route::get('sub-admins', [UcSubAdminController::class, 'index'])->middleware('permission:sub_admins,view');
    Route::post('sub-admins', [UcSubAdminController::class, 'store'])->middleware('permission:sub_admins,edit');
    Route::get('sub-admins/{id}', [UcSubAdminController::class, 'show'])->middleware('permission:sub_admins,view');
    Route::put('sub-admins/{id}', [UcSubAdminController::class, 'update'])->middleware('permission:sub_admins,edit');
    Route::delete('sub-admins/{id}', [UcSubAdminController::class, 'destroy'])->middleware('permission:sub_admins,delete');
});

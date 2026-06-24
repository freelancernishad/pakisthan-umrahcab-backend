<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Api\UmrahCab\UcDriverController;
use App\Http\Controllers\Api\UmrahCab\UcDriverEntryController;

Route::prefix('admin')->middleware(AuthenticateAdmin::class)->group(function () {
    // 1. Drivers Management
    Route::get('drivers', [UcDriverController::class, 'index'])->middleware('permission:drivers,view');
    Route::post('drivers', [UcDriverController::class, 'store'])->middleware('permission:drivers,edit');
    Route::get('drivers/{id}', [UcDriverController::class, 'show'])->middleware('permission:drivers,view');
    Route::put('drivers/{id}', [UcDriverController::class, 'update'])->middleware('permission:drivers,edit');
    Route::delete('drivers/{id}', [UcDriverController::class, 'destroy'])->middleware('permission:drivers,delete');

    // 2. Driver Entries Management (Daily logs submitted by drivers)
    Route::get('driver-entries', [UcDriverEntryController::class, 'index'])->middleware('permission:drivers,view');
    Route::post('driver-entries', [UcDriverEntryController::class, 'store'])->middleware('permission:drivers,edit');
    Route::get('driver-entries/{id}', [UcDriverEntryController::class, 'show'])->middleware('permission:drivers,view');
    Route::put('driver-entries/{id}', [UcDriverEntryController::class, 'update'])->middleware('permission:drivers,edit');
    Route::delete('driver-entries/{id}', [UcDriverEntryController::class, 'destroy'])->middleware('permission:drivers,delete');
    Route::post('driver-entries/{id}/toggle-lock', [UcDriverEntryController::class, 'toggleLock'])->middleware('permission:drivers,edit');
});

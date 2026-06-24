<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateDriver;
use App\Http\Controllers\Api\UmrahCab\UcDriverEntryController;

Route::prefix('driver-panel')->middleware(AuthenticateDriver::class)->group(function () {
    Route::get('entries', [UcDriverEntryController::class, 'myEntries']);
    Route::post('entries', [UcDriverEntryController::class, 'submitEntry']);
    Route::put('entries/{id}', [UcDriverEntryController::class, 'updateEntry']);
});

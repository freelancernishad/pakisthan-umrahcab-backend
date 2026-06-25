<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Api\UmrahCab\UcWebsiteSettingController;

// 1. Admin Protected Route to save/update website settings
Route::prefix('admin')->middleware(AuthenticateAdmin::class)->group(function () {
    Route::post('website-settings', [UcWebsiteSettingController::class, 'storeOrUpdate']);
});

// 2. Public Unprotected Route to fetch website settings (for website headers, logo, metadata)
Route::get('public/website-settings', [UcWebsiteSettingController::class, 'index']);

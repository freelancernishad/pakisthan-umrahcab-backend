<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcPriceListController;
use App\Http\Middleware\AuthenticateAdminOrCompany;
use App\Http\Middleware\AuthenticateAdmin;

Route::middleware(AuthenticateAdminOrCompany::class)->group(function () {
    Route::get('/locations', [UcPriceListController::class, 'locations']);
    Route::get('/price-list', [UcPriceListController::class, 'index']);
});

Route::middleware(AuthenticateAdmin::class)->group(function () {
    Route::post('/price-list', [UcPriceListController::class, 'store']);
    Route::put('/price-list/{id}', [UcPriceListController::class, 'update']);
    Route::delete('/price-list/{id}', [UcPriceListController::class, 'destroy']);
    Route::post('/price-list/bulk', [UcPriceListController::class, 'applyBulkDates']);
});

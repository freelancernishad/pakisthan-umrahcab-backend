<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcPriceListController;

Route::get('/locations', [UcPriceListController::class, 'locations']);
Route::get('/price-list', [UcPriceListController::class, 'index']);
Route::post('/price-list', [UcPriceListController::class, 'store']);
Route::put('/price-list/{id}', [UcPriceListController::class, 'update']);
Route::delete('/price-list/{id}', [UcPriceListController::class, 'destroy']);
Route::post('/price-list/bulk', [UcPriceListController::class, 'applyBulkDates']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcPriceListController;

Route::get('/price-list', [UcPriceListController::class, 'index']);
Route::put('/price-list/{id}', [UcPriceListController::class, 'update']);
Route::post('/price-list/bulk', [UcPriceListController::class, 'applyBulkDates']);

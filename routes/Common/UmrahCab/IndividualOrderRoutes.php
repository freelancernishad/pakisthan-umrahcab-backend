<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcIndividualOrderController;
use App\Http\Controllers\Api\UmrahCab\UcLocationController;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateAdmin;

// Public routes for guest order placement and invoice views
Route::post('/individual-orders', [UcIndividualOrderController::class, 'store']);
Route::get('/individual-orders/invoice/{code}', [UcIndividualOrderController::class, 'getInvoiceDetails']);
Route::post('/individual-orders/pay/{code}', [UcIndividualOrderController::class, 'payInvoice']);

// Protected admin routes for managing locations and viewing orders
Route::middleware([AttachJwtFromCookie::class, AuthenticateAdmin::class])->group(function () {
    Route::get('/admin/locations-list', [UcLocationController::class, 'index']);
    Route::post('/admin/locations-list', [UcLocationController::class, 'store']);
    Route::delete('/admin/locations-list/{id}', [UcLocationController::class, 'destroy']);
    
    Route::get('/admin/individual-orders', [UcIndividualOrderController::class, 'index']);
    Route::get('/admin/individual-orders/{id}', [UcIndividualOrderController::class, 'show']);
    Route::put('/admin/individual-orders/{id}/status', [UcIndividualOrderController::class, 'updateStatus']);
});

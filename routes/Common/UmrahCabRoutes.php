<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcBookingController;
use App\Http\Controllers\Api\UmrahCab\UcCustomerController;
use App\Http\Controllers\Api\UmrahCab\UcCompanyController;
use App\Http\Controllers\Api\UmrahCab\UcServiceController;
use App\Http\Controllers\Api\UmrahCab\UcFlightController;
use App\Http\Controllers\Api\UmrahCab\UcTrainController;
use App\Http\Controllers\Api\UmrahCab\UcInvoiceController;
use App\Http\Controllers\Api\UmrahCab\UcLedgerController;
use App\Http\Controllers\Api\UmrahCab\UcPaymentController;
use App\Http\Controllers\Api\UmrahCab\UcNoticeController;
use App\Http\Controllers\Api\UmrahCab\UcFleetController;
use App\Http\Controllers\Api\UmrahCab\UcAuditController;
use App\Http\Controllers\Api\UmrahCab\UcPriceListController;

use App\Http\Controllers\Api\UmrahCab\UcFollowupController;

Route::prefix('umrahcab')->group(function () {
    // Bookings
    Route::get('/bookings/summary', [UcBookingController::class, 'dashboardSummary']);
    Route::get('/bookings', [UcBookingController::class, 'index']);
    Route::post('/bookings', [UcBookingController::class, 'store']);
    Route::put('/bookings/{id}', [UcBookingController::class, 'update']);
    Route::get('/bookings/status/{code}', [UcBookingController::class, 'getStatus']);

    // Customers
    Route::get('/customers', [UcCustomerController::class, 'index']);
    Route::post('/customers', [UcCustomerController::class, 'store']);
    Route::put('/customers/{id}', [UcCustomerController::class, 'update']);
    Route::get('/customers/{id}', [UcCustomerController::class, 'show']);

    // Companies
    Route::get('/companies', [UcCompanyController::class, 'index']);
    Route::post('/companies', [UcCompanyController::class, 'store']);

    // Services
    Route::get('/services', [UcServiceController::class, 'index']);
    Route::post('/services', [UcServiceController::class, 'store']);
    Route::get('/services/{id}', [UcServiceController::class, 'show']);
    Route::put('/services/{id}', [UcServiceController::class, 'update']);
    Route::delete('/services/{id}', [UcServiceController::class, 'destroy']);

    // Flights
    Route::get('/flights', [UcFlightController::class, 'index']);
    Route::post('/flights', [UcFlightController::class, 'store']);
    Route::get('/flights/{id}', [UcFlightController::class, 'show']);
    Route::put('/flights/{id}', [UcFlightController::class, 'update']);
    Route::delete('/flights/{id}', [UcFlightController::class, 'destroy']);

    // Trains
    Route::get('/trains', [UcTrainController::class, 'index']);
    Route::post('/trains', [UcTrainController::class, 'store']);
    Route::get('/trains/{id}', [UcTrainController::class, 'show']);
    Route::put('/trains/{id}', [UcTrainController::class, 'update']);
    Route::delete('/trains/{id}', [UcTrainController::class, 'destroy']);

    // Invoices
    Route::get('/invoices', [UcInvoiceController::class, 'index']);
    Route::post('/invoices', [UcInvoiceController::class, 'store']);

    // Ledgers
    Route::get('/ledgers', [UcLedgerController::class, 'index']);
    Route::post('/ledgers', [UcLedgerController::class, 'store']);

    // Payments
    Route::get('/payments', [UcPaymentController::class, 'index']);
    Route::post('/payments', [UcPaymentController::class, 'store']);

    // Notices
    Route::get('/notices', [UcNoticeController::class, 'index']);
    Route::post('/notices', [UcNoticeController::class, 'store']);

    // Fleet
    Route::get('/fleet', [UcFleetController::class, 'index']);
    Route::put('/fleet/{id}', [UcFleetController::class, 'update']);

    // Audits
    Route::get('/audits', [UcAuditController::class, 'index']);
    Route::post('/audits', [UcAuditController::class, 'store']);

    // Followups
    Route::get('/followups', [UcFollowupController::class, 'index']);
    Route::post('/followups', [UcFollowupController::class, 'store']);

    // Price List Matrix
    Route::get('/price-list', [UcPriceListController::class, 'index']);
    Route::put('/price-list/{id}', [UcPriceListController::class, 'update']);
    Route::post('/price-list/bulk', [UcPriceListController::class, 'applyBulkDates']);
});

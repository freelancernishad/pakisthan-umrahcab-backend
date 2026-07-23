<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateCompany;
use App\Http\Controllers\Api\UmrahCab\CompanyPanelController;
use App\Http\Controllers\Api\UmrahCab\UcChatController;

Route::middleware([AttachJwtFromCookie::class])->group(function () {
    Route::prefix('company-panel')->middleware(AuthenticateCompany::class)->group(function () {
        Route::get('dashboard-summary', [CompanyPanelController::class, 'dashboardSummary']);
        Route::get('bookings', [CompanyPanelController::class, 'bookings']);
        Route::get('customers', [CompanyPanelController::class, 'customers']);
        Route::post('customers', [CompanyPanelController::class, 'createCustomer']);
        Route::get('customers/{id}', [CompanyPanelController::class, 'customerDetails']);
        Route::get('invoices', [CompanyPanelController::class, 'invoices']);
        Route::get('ledgers', [CompanyPanelController::class, 'ledgers']);
        Route::get('payments', [CompanyPanelController::class, 'payments']);
        Route::post('payments', [CompanyPanelController::class, 'createPayment']);
        
        // Support Chat Routes
        Route::get('chat', [UcChatController::class, 'getCompanyMessages']);
        Route::post('chat', [UcChatController::class, 'sendCompanyMessage']);

        // Upload Document Route
        Route::post('upload-document', [CompanyPanelController::class, 'uploadDocument']);

        // Customer Document Management Routes
        Route::get('customer-documents', [CompanyPanelController::class, 'getDocuments']);
        Route::post('customer-documents', [CompanyPanelController::class, 'storeDocument']);
        Route::delete('customer-documents/{id}', [CompanyPanelController::class, 'deleteDocument']);

        // Drivers List Route
        Route::get('drivers', [\App\Http\Controllers\Api\UmrahCab\UcDriverController::class, 'index']);
    });
});


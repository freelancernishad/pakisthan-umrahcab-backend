<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcInvoiceController;

Route::get('/invoices', [UcInvoiceController::class, 'index']);
Route::get('/invoices/{id}', [UcInvoiceController::class, 'show']);
Route::post('/invoices/calculate', [UcInvoiceController::class, 'calculate']);
Route::post('/invoices', [UcInvoiceController::class, 'store']);
Route::put('/invoices/{id}', [UcInvoiceController::class, 'update']);
Route::delete('/invoices/{id}', [UcInvoiceController::class, 'destroy']);

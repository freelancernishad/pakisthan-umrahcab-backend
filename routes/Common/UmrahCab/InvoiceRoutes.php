<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcInvoiceController;

Route::get('/invoices', [UcInvoiceController::class, 'index']);
Route::post('/invoices', [UcInvoiceController::class, 'store']);

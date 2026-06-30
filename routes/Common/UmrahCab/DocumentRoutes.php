<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcDocumentController;

Route::get('/customer-documents', [UcDocumentController::class, 'index']);
Route::post('/customer-documents', [UcDocumentController::class, 'store']);
Route::delete('/customer-documents/{id}', [UcDocumentController::class, 'destroy']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcLedgerController;

Route::get('/ledgers', [UcLedgerController::class, 'index']);
Route::get('/ledgers/direct-clients', [UcLedgerController::class, 'directClientsLedger']);
Route::post('/ledgers', [UcLedgerController::class, 'store']);


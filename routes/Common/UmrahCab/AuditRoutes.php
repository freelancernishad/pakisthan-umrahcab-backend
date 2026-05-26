<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcAuditController;

Route::get('/audits', [UcAuditController::class, 'index']);
Route::post('/audits', [UcAuditController::class, 'store']);

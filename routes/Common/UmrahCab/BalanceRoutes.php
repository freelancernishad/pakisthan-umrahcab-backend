<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmrahCab\UcBalanceController;

Route::get('/balance/summary', [UcBalanceController::class, 'summary']);

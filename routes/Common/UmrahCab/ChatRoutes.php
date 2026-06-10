<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Api\UmrahCab\UcChatController;

Route::prefix('chat/admin')->middleware(AuthenticateAdmin::class)->group(function () {
    Route::get('/', [UcChatController::class, 'getAdminRooms']);
    Route::get('{company_id}', [UcChatController::class, 'getAdminMessages']);
    Route::post('{company_id}', [UcChatController::class, 'sendAdminMessage']);
});

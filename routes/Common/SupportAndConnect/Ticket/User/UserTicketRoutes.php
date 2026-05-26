<?php


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\Common\SupportAndConnect\User\SupportTicketApiController;
use App\Http\Controllers\Common\SupportAndConnect\Admin\AdminSupportTicketApiController;



Route::prefix('user')->group(function () {
    Route::middleware(AuthenticateUser::class)->group(function () {

        Route::get('/support', [SupportTicketApiController::class, 'index']);
        Route::post('/support', [SupportTicketApiController::class, 'store']);
        Route::get('/support/{ticket}', [SupportTicketApiController::class, 'show']);
        Route::post('/support/{ticket}/reply', [AdminSupportTicketApiController::class, 'reply']);


    });

});

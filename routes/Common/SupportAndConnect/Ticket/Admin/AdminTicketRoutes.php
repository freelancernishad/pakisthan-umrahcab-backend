<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\SupportAndConnect\Admin\AdminSupportTicketApiController;
use App\Http\Middleware\AuthenticateAdmin;

Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {

        // Support ticket routes
        Route::get('/support', [AdminSupportTicketApiController::class, 'index']);
        Route::get('/support/{ticket}', [AdminSupportTicketApiController::class, 'show']);
        Route::post('/support/{ticket}/reply', [AdminSupportTicketApiController::class, 'reply']);
        Route::patch('/support/{ticket}/status', [AdminSupportTicketApiController::class, 'updateStatus']);



    });

});

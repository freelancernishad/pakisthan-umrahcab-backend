<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Common\Twilio\TwilioController;


Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {
        Route::post('twilio/send-sms', [TwilioController::class, 'send']);
    });
});

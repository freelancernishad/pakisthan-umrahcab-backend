<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins\EmailTemplateController;
use App\Http\Controllers\Admins\EmailSenderController;
use App\Http\Controllers\Admins\EmailLogController;

/*
|--------------------------------------------------------------------------
| Email Template API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(App\Http\Middleware\AuthenticateAdmin::class)->group(function () {
    // Email Template CRUD API (except index)
    Route::post('/email-templates', [EmailTemplateController::class, 'store'])->name('admin.email-templates.store');
    Route::get('/email-templates/create', [EmailTemplateController::class, 'create'])->name('admin.email-templates.create');
    Route::get('/email-templates/{email_template}', [EmailTemplateController::class, 'show'])->name('admin.email-templates.show');
    Route::get('/email-templates/{email_template}/edit', [EmailTemplateController::class, 'edit'])->name('admin.email-templates.edit');
    Route::put('/email-templates/{email_template}', [EmailTemplateController::class, 'update'])->name('admin.email-templates.update');
    Route::patch('/email-templates/{email_template}', [EmailTemplateController::class, 'update']);
    Route::delete('/email-templates/{email_template}', [EmailTemplateController::class, 'destroy'])->name('admin.email-templates.destroy');

    // Email Sender API
    Route::post('/email-sender/send', [EmailSenderController::class, 'send'])->name('admin.email-sender.send');
    Route::post('/email-sender/test', [EmailSenderController::class, 'sendTest'])->name('admin.email-sender.test');
    Route::post('/email-sender/validate', [EmailSenderController::class, 'validateEmail'])->name('admin.email-sender.validate');

    // Email Logs API
    Route::delete('/email-history/{emailLog}', [EmailLogController::class, 'destroy'])->name('admin.email-logs.destroy');
});

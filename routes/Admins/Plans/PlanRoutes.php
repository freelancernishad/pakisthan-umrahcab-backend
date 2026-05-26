<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Admin\Plans\PlanController;

Route::get('plans/list', [PlanController::class, 'index']); // Public/User plan list

Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {
        Route::prefix('plans')->group(function () {
            Route::get('/', [PlanController::class, 'index']);  // List all plans
            Route::get('{id}', [PlanController::class, 'show']); // Get single plan by ID
            Route::post('/', [PlanController::class, 'store']);  // Create new plan
            Route::put('{id}', [PlanController::class, 'update']); // Update existing plan
            Route::delete('{id}', [PlanController::class, 'destroy']); // Delete a plan
        });
    });
});

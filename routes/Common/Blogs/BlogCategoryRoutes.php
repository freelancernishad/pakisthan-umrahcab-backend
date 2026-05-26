<?php


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Common\Blogs\BlogCategory\BlogCategoryController;

Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {

                // Admin routes for blog categories
        Route::group(['prefix' => 'blogs/categories',], function () {
            Route::get('/', [BlogCategoryController::class, 'index']);
            Route::post('/', [BlogCategoryController::class, 'store']);
            Route::get('/{id}', [BlogCategoryController::class, 'show']);
            Route::put('/{id}', [BlogCategoryController::class, 'update']);
            Route::delete('/{id}', [BlogCategoryController::class, 'destroy']);
            Route::get('/all/list', [BlogCategoryController::class, 'list']);
            Route::put('/reassign-update/{id}', [BlogCategoryController::class, 'reassignAndUpdateParent']);
        });




    });
});

Route::prefix('user')->group(function () {
    Route::middleware(AuthenticateUser::class)->group(function () {

    });
});


Route::prefix('blogs/categories')->group(function () {
    Route::get('/', [BlogCategoryController::class, 'index']);
    Route::get('/{id}', [BlogCategoryController::class, 'show']);
    Route::get('/all/list', [BlogCategoryController::class, 'list']);
});

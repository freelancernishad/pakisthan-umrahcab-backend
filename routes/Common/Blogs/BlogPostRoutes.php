<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Controllers\Common\Blogs\BlogPost\BlogPostController;

Route::prefix('admin')->group(function () {
    Route::middleware(AuthenticateAdmin::class)->group(function () {

        Route::prefix('blogs/articles')->group(function () {
            Route::get('/', [BlogPostController::class, 'index']);
            Route::post('/', [BlogPostController::class, 'store']);
            Route::get('{id}', [BlogPostController::class, 'show']);
            Route::post('{id}', [BlogPostController::class, 'update']);
            Route::delete('{id}', [BlogPostController::class, 'destroy']);

            // Add or remove categories to/from articles
            Route::post('{id}/add-category', [BlogPostController::class, 'addCategory']);
            Route::post('{id}/remove-category', [BlogPostController::class, 'removeCategory']);

            Route::get('/by-category/with-child-articles', [BlogPostController::class, 'getArticlesByCategory']);
        });
    });
});

Route::prefix('user')->group(function () {
    Route::middleware(AuthenticateUser::class)->group(function () {

    });
});


Route::prefix('blogs/articles')->group(function () {
    Route::get('/', [BlogPostController::class, 'index']);
    Route::get('{id}', [BlogPostController::class, 'show']);
    Route::get('/by-category/with-child-articles', [BlogPostController::class, 'getArticlesByCategory']);

});

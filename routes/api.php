<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1'
], function () {
    Route::group([
        'middleware' => 'jwt.auth',
    ], function () {
        Route::group([
            'prefix' => 'auth',
        ], function () {
            Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('jwt.auth');
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me', [AuthController::class, 'me']);
        });

        Route::get('products/all', [ProductController::class, 'all']);
        Route::apiResource('products', ProductController::class);
    });
});

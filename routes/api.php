<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/login', [UserController::class, 'logIn']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/signup', [UserController::class, 'signUp']);
    Route::post('/logout', [UserController::class, 'logOut']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::prefix('admin')->group(function () {
            Route::prefix('pharmacy')->group(function () {
                
            });
            Route::prefix('generic')->group(function () {


            });
            Route::prefix('manufacturer')->group(function () {


            });
            Route::prefix('supplier')->group(function () {


            });
            Route::prefix('product')->group(function () {


            });
            Route::prefix('inventory')->group(function () {


            });
        });

});

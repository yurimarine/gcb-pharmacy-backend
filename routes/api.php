<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/login', [UserController::class, 'logIn']);

Route::middleware('auth:sanctum')->group(function () {

 Route::post('/signup', [UserController::class, 'signUp']);
 Route::post('/logout', [UserController::class, 'logOut']);
 Route::post('/change-password', [UserController::class, 'changePassword']);

});

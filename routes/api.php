<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\GenericController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;



Route::post('/login', [UserController::class, 'logIn']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/signup', [UserController::class, 'signUp']);
    Route::post('/logout', [UserController::class, 'logOut']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::prefix('admin')->group(function () {
            Route::prefix('category')->group(function () {
                Route::post('/add', [CategoryController::class, 'addCategory']);
                Route::put('/update/{id}', [CategoryController::class, 'updateCategory']);
                Route::delete('/delete/{id}', [CategoryController::class, 'deleteCategory']);
            });
            Route::prefix('pharmacy')->group(function () {
                Route::post('/add', [PharmacyController::class, 'addPharmacy']);
                Route::put('/update/{id}', [PharmacyController::class, 'updatePharmacy']);
                Route::delete('/delete/{id}', [PharmacyController::class, 'deletePharmacy']);
            });
            Route::prefix('generic')->group(function () {
                Route::post('/add', [GenericController::class, 'addGeneric']);
                Route::put('/update/{id}', [GenericController::class, 'updateGeneric']);
                Route::delete('/delete/{id}', [GenericController::class, 'deleteGeneric']);
            });
            Route::prefix('manufacturer')->group(function () {
                Route::post('/add', [ManufacturerController::class, 'addManufacturer']);
                Route::put('/update/{id}', [ManufacturerController::class, 'updateManufacturer']);
                Route::delete('/delete/{id}', [ManufacturerController::class, 'deleteManufacturer']);
            });
            Route::prefix('supplier')->group(function () {
                Route::post('/add', [SupplierController::class, 'addSupplier']);
                Route::put('/update/{id}', [SupplierController::class, 'updateSupplier']);
                Route::delete('/delete/{id}', [SupplierController::class, 'deleteSupplier']);
            });
            Route::prefix('product')->group(function () {
                Route::post('/add', [ProductController::class, 'addProduct']);
                Route::put('/update/{id}', [ProductController::class, 'updateProduct']);
                Route::delete('/delete/{id}', [ProductController::class, 'deleteProduct']);
            });
            Route::prefix('inventory')->group(function () {
                Route::put('/update/{pharmacyId}/{productId}', [InventoryController::class, 'updateInventory']);
            });
        });

});
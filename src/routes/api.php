<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControllers\AuthController;
use App\Http\Controllers\AuthControllers\UserController;
use App\Http\Controllers\MarketControllers\CartController;
use App\Http\Controllers\MarketControllers\OrderController;
use App\Http\Controllers\MarketControllers\ProductController;
use App\Http\Controllers\AdminControllers\AdminProductController;
use App\Http\Controllers\AdminControllers\AdminUsersController;
use App\Http\Controllers\AdminControllers\AdminCategoryController;

Route::get('/user', function (Request $request) {
    return "testy";
});

Route::middleware('admin')->group(function () {

    // Admin routes for Users
    Route::post('/admin/users',[AdminUsersController::class,'index']);
    Route::post('/admin/users/create',[AdminUsersController::class,'create']);
    Route::post('/admin/users/{id}',[AdminUsersController::class,'show']);
    Route::put('/admin/users/{id}',[AdminUsersController::class,'update']);
    Route::delete('/admin/users/{id}',[AdminUsersController::class,'destroy']);
    // Admin routes for Products
    Route::post('/admin/products', [AdminProductController::class,'index']);
    Route::post('/admin/products/{id}',[AdminProductController::class,'show']);
    Route::post('/admin/products/create', [AdminProductController::class, 'create']);
    Route::put('/admin/products/{id}', [AdminProductController::class, 'update']);
    Route::delete('/admin/products/{id}',[AdminProductController::class,'destroy']);
    Route::post('/admin/categories', [AdminCategoryController::class,'index']);
    Route::post('/admin/categories/create', [AdminCategoryController::class, 'create']);
    Route::post('/admin/categories/{id}', [AdminCategoryController::class, 'show']);
    Route::put('/admin/categories/{id}', [AdminCategoryController::class, 'update']);
    Route::delete('/admin/categories/{id}', [AdminCategoryController::class, 'destroy']);

});

Route::post('/cart',[CartController::class,'index']);

// POST Auth Requests
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

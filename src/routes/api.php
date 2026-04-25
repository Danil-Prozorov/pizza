<?php

use App\Http\Controllers\AdminControllers\AdminOrderController;
use App\Http\Controllers\AdminControllers\AdminProductController;
use App\Http\Controllers\AdminControllers\AdminUsersController;
use App\Http\Controllers\AuthControllers\AuthController;
use App\Http\Controllers\MarketControllers\CartController;
use App\Http\Controllers\MarketControllers\OrderController;
use App\Http\Controllers\MarketControllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return "";
});

Route::middleware('admin')->group(function () {

    // Admin routes for Users
    Route::post('/admin/users',[AdminUsersController::class,'index']);
    Route::post('/admin/users/create',[AdminUsersController::class,'create']);
    Route::post('/admin/users/{id}',[AdminUsersController::class,'show']);
    Route::put('/admin/users/{id}',[AdminUsersController::class,'update']);
    Route::delete('/admin/users/{id}',[AdminUsersController::class,'destroy']);
    // Admin routes for Products and Categories
    Route::post('/admin/products', [AdminProductController::class,'index']);
    Route::post('/admin/products/create', [AdminProductController::class, 'create']);
    Route::post('/admin/products/{id}',[AdminProductController::class,'show']);
    Route::put('/admin/products/{id}', [AdminProductController::class, 'update']);
    Route::delete('/admin/products/{id}',[AdminProductController::class,'destroy']);
    /*Route::post('/admin/categories', [AdminCategoryController::class,'index']);
    Route::post('/admin/categories/create', [AdminCategoryController::class, 'create']);
    Route::post('/admin/categories/{id}', [AdminCategoryController::class, 'show']);
    Route::put('/admin/categories/{id}', [AdminCategoryController::class, 'update']);
    Route::delete('/admin/categories/{id}', [AdminCategoryController::class, 'destroy']); */
    // Admin routes for Orders
    Route::post('/admin/orders', [AdminOrderController::class,'index']);
    Route::post('/admin/orders/create', [AdminOrderController::class, 'create']);
    Route::put('/admin/orders/product',[AdminOrderController::class, 'updateProducts']);
    Route::post('/admin/orders/{id}',[AdminOrderController::class, 'show']);
    Route::put('/admin/orders/{id}', [AdminOrderController::class, 'update']);
    Route::delete('/admin/orders/{id}', [AdminOrderController::class, 'destroy']);
});

// Category Routes
/* Route::post('/category/',[CategoryController::class,'index']);
Route::post('/category/{category}',[CategoryController::class,'show']); */

// Public product routes
Route::post('/product/{id}',[ProductController::class,'show']);

Route::middleware('jwt')->group(function () {
    // Public cart routes
    Route::post('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'store']);
    Route::delete('/cart/remove', [CartController::class, 'destroy']);

    // Public Order routes
    Route::post('/order', [OrderController::class, 'index']);
    Route::post('/order/create', [OrderController::class, 'create']);
    Route::post('/order/{id}', [OrderController::class, 'show']);
    Route::delete('/order/{id}', [OrderController::class, 'delete']);
});

// POST Auth Requests
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

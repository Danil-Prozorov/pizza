<?php

use App\Http\Controllers\AuthControllers\AuthController;
use App\Http\Controllers\AuthControllers\UserController;
use App\Http\Controllers\MarketControllers\CartController;
use App\Http\Controllers\MarketControllers\OrderController;
use App\Http\Controllers\MarketControllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


// Views
Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::get('/login',[AuthController::class,'loginView'])->name('auth.login');
Route::get('/register',[AuthController::class,'registerView'])->name('auth.register');

// POST Requests
Route::post('/register', [AuthController::class, 'register'])->name('auth.registerRequest');
Route::post('/login', [AuthController::class, 'login'])->name('auth.loginRequest');

Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

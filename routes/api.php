<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth')->group(function() {
    // Products
    Route::resource('/products', 'ProductController')->only([
        'store', 'update', 'destroy'
    ]);

    // Category
    Route::resource('/categories', 'CategoryController')->only([
        'store', 'update', 'destroy'
    ]);
});

// Products
Route::resource('/products', 'ProductController')->only([
    'index', 'show'
]);

Route::resource('/categories', 'CategoryController')->only([
    'index', 'show'
]);

// User
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/authSessionKey', [LoginController::class, 'authSessionKey']);
Route::post('/authUserByEmail', [LoginController::class, 'authUserByEmail']);

// Авторизация по sessionKey
Route::post('/orderCreate', [OrderController::class, 'create']);

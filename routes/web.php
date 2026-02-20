<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('category')->group(function () {
    Route::get('/food', [ProductController::class, 'food']);
    Route::get('/beauty', [ProductController::class, 'beauty']);
    Route::get('/care', [ProductController::class, 'home']);
    Route::get('/baby', [ProductController::class, 'baby']);
});

Route::get('/user/{id}/name/{name}', [UserController::class, 'profile']);
<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
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

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

route::get('/', [WelcomeController::class, 'index']);

route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/list', [UserController::class, 'list']);
    Route::get('/create', [UserController::class, 'create']);
    Route::get('/', [UserController::class, 'strore']);
    Route::get('/{$id}', [UserController::class, 'show']);
    Route::get('/{$id}/edit', [UserController::class, 'edit']);
    Route::get('/{$id}', [UserController::class, 'update']);
    Route::get('/{$id}', [UserController::class, 'destroy']);
});
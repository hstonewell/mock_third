<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

//ログイン・認証
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

<?php

use App\Http\Controllers\LoginController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

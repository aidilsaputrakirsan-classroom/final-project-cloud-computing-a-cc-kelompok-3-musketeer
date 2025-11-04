<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\FAQController;

// Redirect ke halaman login jika mengakses root
Route::get('/', function() {
    return redirect('/login');
});

// Dashboard (hanya bisa diakses jika sudah login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// Login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');

// FAQ / Bantuan
Route::get('/faq', [FAQController::class, 'index'])->name('faq.index');
Route::get('/faq/{id}', [FAQController::class, 'show'])->name('faq.show');

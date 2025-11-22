<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

// Redirect ke halaman login jika mengakses root
Route::get('/', function() {
    return redirect('/login');
});

// Admin
Route::middleware([\Illuminate\Auth\Middleware\Authenticate::class, \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // === LAPORAN PENDING ===
        Route::get('/reports', [AdminReportController::class, 'index'])
            ->name('reports.index');

        // === LAPORAN HISTORY (ACCEPTED / REJECTED) ===
        Route::get('/reports/history', [AdminReportController::class, 'history'])
            ->name('reports.history');

        // DETAIL HISTORY
        Route::get('/reports/history/{report}', [AdminReportController::class, 'showHistory'])
            ->name('reports.history.show');

        // DETAIL PENDING
        Route::get('/reports/{report}', [AdminReportController::class, 'show'])
            ->name('reports.show');

        // UBAH STATUS (TERIMA / TOLAK) UNTUK PENDING
        // (sekaligus hapus post jika status = accepted, sesuai logic di Admin\ReportController::changeStatus)
        Route::post('/reports/{report}/status', [AdminReportController::class, 'changeStatus'])
            ->name('reports.status');
    });

// Comment
Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Category
Route::get('/topics', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/topics/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');

// FAQ / Bantuan
Route::get('/faq', [FAQController::class, 'index'])->name('faq.index');
Route::get('/faq/{id}', [FAQController::class, 'show'])->name('faq.show');

// Profile routes
Route::get('/my-posts', [ProfileController::class, 'myPosts'])->name('my-posts')->middleware('auth');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

// Posts routes (keep for create, edit, show, delete)
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show')->middleware('auth');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit')->middleware('auth');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update')->middleware('auth');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware('auth');

// Laporan dari user ke post
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/report', [ReportController::class, 'store'])->name('reports.store');
});

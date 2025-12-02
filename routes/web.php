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
use App\Http\Controllers\PostReactionController;

/*
|--------------------------------------------------------------------------
| Redirect root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/history', [AdminReportController::class, 'history'])->name('reports.history');
        Route::get('/reports/history/{report}', [AdminReportController::class, 'showHistory'])->name('reports.history.show');
        Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::post('/reports/{report}/status', [AdminReportController::class, 'changeStatus'])->name('reports.status');
    });

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/
Route::get('/topics', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/topics/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

/*
|--------------------------------------------------------------------------
| Authentication & Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

Route::get('/login', [LoginController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])
    ->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/register', [RegisterController::class, 'index'])
    ->name('register')
    ->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('guest');

/*
|--------------------------------------------------------------------------
| FAQ
|--------------------------------------------------------------------------
*/
Route::get('/faq', [FAQController::class, 'index'])->name('faq.index');
Route::get('/faq/{id}', [FAQController::class, 'show'])->name('faq.show');

/*
|--------------------------------------------------------------------------
| Profile (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // halaman postingan saya
    Route::get('/my-posts', [ProfileController::class, 'myPosts'])->name('my-posts');

    // edit profile (private)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| POSTS (create/edit/delete) — must login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // form & simpan postingan baru
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // edit / update / delete
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Report post
    Route::post('/posts/{post}/report', [ReportController::class, 'store'])->name('reports.store');
});

/*
|--------------------------------------------------------------------------
| COMMENTS (must login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

/*
|--------------------------------------------------------------------------
| LIKE / DISLIKE SYSTEM (must login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // React to post (AJAX)
    Route::post('/posts/{post}/react', [PostReactionController::class, 'react'])
        ->name('posts.react');

    // Detail daftar siapa saja yang bereaksi ke 1 post
    Route::get('/posts/{post}/reactions', [PostReactionController::class, 'showReactions'])
        ->name('posts.reactions');

    // Halaman "Daftar Suka"
    Route::get('/daftar-suka', [PostReactionController::class, 'myPostReactions'])
        ->name('user.reactions.index');

    // Alias URL /my-reactions
    Route::get('/my-reactions', [PostReactionController::class, 'myPostReactions'])
        ->name('user.reactions');
});

/*
|--------------------------------------------------------------------------
<<<<<<< HEAD
| Public profile (anyone can view a user's public profile)
| - This allows links like route('profile.show', $user->id) to work for public profiles.
| - Put this before the generic post show route.
|--------------------------------------------------------------------------
*/
Route::get('/users/{user}', [ProfileController::class, 'show'])
    ->name('profile.show');
=======
| NOTIFICATIONS (AJAX mark all as read)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->post('/notifications/read-all', function () {
    $user = auth()->user();
    if ($user) {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }
    return response()->json(['success' => true]);
})->name('notifications.readAll');
>>>>>>> development

/*
|--------------------------------------------------------------------------
| PUBLIC — anyone can see a single post
<<<<<<< HEAD
| LETAKKAN PALING BAWAH, setelah semua /posts/... lain
=======
>>>>>>> development
|--------------------------------------------------------------------------
*/
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

<?php

use App\Http\Controllers\FAQController;
use Illuminate\Support\Facades\Route;

Route::get('/faq', [FAQController::class, 'index'])->name('faq.index');
Route::get('/faq/{slug}', [FAQController::class, 'show'])->name('faq.show');

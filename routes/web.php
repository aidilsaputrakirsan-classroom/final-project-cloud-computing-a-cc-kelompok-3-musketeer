<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tampilan_komentar', function () {
    return view('post.tampilan_komentar');
});
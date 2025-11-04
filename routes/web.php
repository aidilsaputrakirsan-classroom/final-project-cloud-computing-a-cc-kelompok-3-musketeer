<?php

use Illuminate\Support\Facades\Route;

Route::get('/tampilan_komentar', function () {
    return view('post.tampilan_komentar');
});

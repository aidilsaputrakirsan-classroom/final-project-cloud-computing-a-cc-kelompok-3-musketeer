<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

public function index()
{
    $user = auth()->user();

    // Ambil postingan terbaru dengan jumlah komentar
    $posts = Post::with('user')
        ->withCount('comments')
        ->latest()
        ->paginate(10);

    // Ambil semua notifikasi user
    $notifications = $user ? $user->notifications()->latest()->get() : collect();

    // Hitung notifikasi yang belum dibaca
    $unreadCount = $user ? $user->unreadNotifications()->count() : 0;

    return view('dashboard', compact('posts', 'notifications', 'unreadCount'));
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with posts.
     */
    public function index()
    {
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('posts'));
    }
}

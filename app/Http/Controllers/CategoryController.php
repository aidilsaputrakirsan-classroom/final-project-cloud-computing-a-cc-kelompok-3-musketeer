<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori (Jelajahi Topik).
     */
    public function index()
    {
        $categories = Category::withCount('posts')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Tampilkan postingan berdasarkan kategori (slug).
     */
    public function show(Category $category)
    {
        $posts = Post::with(['user', 'category'])
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'posts'));
    }
    
}

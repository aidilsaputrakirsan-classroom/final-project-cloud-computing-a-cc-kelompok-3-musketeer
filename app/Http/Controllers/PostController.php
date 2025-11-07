<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        $validated['user_id'] = Auth::id();

        try {
            Post::create($validated);
            return redirect()->route('dashboard')
                ->with('success', 'Postingan berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal membuat postingan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // muat relasi post dengan user dan komentar beserta usernya
        // termasuk komentar anak (replies)
        $post->load(['user']);

        // tambahkan views count
        $post->increment('views');

        // ambil komentar top-level (parent_id = null)
        $comments = $post->comments()
            ->whereNull('parent_id')
            ->with(['user', 'children.user'])
            ->latest()
            ->get();

        // hitung jumlah komentar
        $post->comments_count = $post->comments()->count();

        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        try {
            $post->update($validated);
            return redirect()->route('my-posts')
                ->with('success', 'Postingan berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui postingan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $post->delete();
            return redirect()->route('my-posts')
                ->with('success', 'Postingan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('my-posts')
                ->with('error', 'Gagal menghapus postingan: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\ActivityLog; // <<< TAMBAH INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'category'])
            ->where('user_id', Auth::id()) // hanya postingan milik user login
            ->latest()
            ->paginate(10);

        return view('my-posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ambil semua kategori untuk dropdown
        $categories = Category::all();

        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'nullable|exists:categories,id', // relasi baru
        ]);

        $validated['user_id'] = Auth::id();

        try {
            // simpan post dan ambil instance-nya
            $post = Post::create($validated);

            // === ACTIVITY LOG: post.created ===
            ActivityLog::record(
                action: 'post.created',
                description: 'User membuat postingan baru',
                context: 'post',
                detail: [
                    'post_id' => $post->id,
                    'title'   => $post->title,
                ]
            );

            // redirect ke dashboard (ada di projectmu)
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
        $post->load(['user', 'category']); // tambahkan relasi category

        // Tambahkan views count
        $post->increment('views');

        // Ambil komentar top-level (parent_id = null)
        $comments = $post->comments()
            ->whereNull('parent_id')
            ->with(['user', 'children.user'])
            ->latest()
            ->get();

        // Hitung jumlah komentar (tidak menyimpan, hanya untuk tampilan)
        $post->comments_count = $post->comments()->count();

        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        try {
            // simpan data lama dulu jika kamu ingin bandingkan manual (optional)
            // $original = $post->getOriginal();

            $post->update($validated);

            // ambil perubahan yang terjadi (include updated_at)
            $changes = $post->getChanges();

            // === ACTIVITY LOG: post.updated ===
            ActivityLog::record(
                action: 'post.updated',
                description: 'User mengubah postingan',
                context: 'post',
                detail: [
                    'post_id' => $post->id,
                    'changes' => $changes,
                ]
            );

            // arahkan ke dashboard agar tidak bergantung pada route 'my-posts.index'
            return redirect()->route('dashboard')
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
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $postId = $post->id; // simpan dulu id-nya

            $post->delete();

            // === ACTIVITY LOG: post.deleted ===
            ActivityLog::record(
                action: 'post.deleted',
                description: 'User menghapus postingan',
                context: 'post',
                detail: [
                    'post_id' => $postId,
                ]
            );

            // gunakan dashboard atau back() — dashboard biasanya ada di projectmu
            return redirect()->route('dashboard')
                ->with('success', 'Postingan berhasil dihapus!');
        } catch (\Exception $e) {
            // jika terjadi error, kembali ke halaman sebelumnya agar tidak memicu RouteNotFoundException
            return redirect()->back()
                ->with('error', 'Gagal menghapus postingan: ' . $e->getMessage());
        }
    }
}

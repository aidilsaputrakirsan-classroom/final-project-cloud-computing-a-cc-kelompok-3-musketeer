<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\ActivityLog; // <<< TAMBAH INI
use Illuminate\Support\Facades\Auth;

class PostReactionController extends Controller
{
    /**
     * AJAX: like / dislike / remove reaction
     */
    public function react(Request $request, Post $post)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $type = $request->input('type'); // 'like', 'dislike', 'remove'
        if (!in_array($type, ['like', 'dislike', 'remove'])) {
            return response()->json(['message' => 'Tipe tidak valid'], 422);
        }

        try {
            // query semua reaction user untuk post ini
            $query    = Reaction::where('post_id', $post->id)
                                ->where('user_id', $user->id);
            $existing = $query->first();

            if ($type === 'remove') {
                // hapus SEMUA reaksi user di post ini
                $query->delete();
                // NOTE: tidak ada activity log untuk remove (sesuai spesifikasi)
            } else {
                $value = $type === 'like' ? 1 : -1;

                if ($existing && $existing->reaction == $value) {
                    // klik ulang reaksi yang sama -> toggle OFF
                    $query->delete();
                    // tidak log apa-apa di sini
                } else {
                    // ganti reaksi: hapus semua, lalu buat 1 reaksi baru
                    $query->delete();

                    $reaction = Reaction::create([
                        'post_id'  => $post->id,
                        'user_id'  => $user->id,
                        'reaction' => $value,
                    ]);

                    // === ACTIVITY LOG: reaction.added ===
                    ActivityLog::record(
                        action: 'reaction.added',
                        description: 'User memberi reaksi pada postingan',
                        context: 'reaction',
                        detail: [
                            'post_id'  => $post->id,
                            'reaction' => $value, // 1 = like, -1 = dislike
                        ]
                    );
                }
            }

            // hitung ulang dari database (pasti hanya 0/1 record per user)
            $likes    = Reaction::where('post_id', $post->id)->where('reaction', 1)->count();
            $dislikes = Reaction::where('post_id', $post->id)->where('reaction', -1)->count();

            $current = Reaction::where('post_id', $post->id)
                ->where('user_id', $user->id)
                ->first();

            return response()->json([
                'likes'         => $likes,
                'dislikes'      => $dislikes,
                'user_reaction' => $current ? $current->reaction : 0, // 1 / -1 / 0
            ], 200);

        } catch (\Throwable $e) {
            \Log::error('React error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memproses reaksi'], 500);
        }
    }

    /**
     * Halaman "Daftar Suka" (semua postingan milik user + reaksi orang)
     */
    public function myPostReactions()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $posts = Post::with(['reactions.user'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // NOTE: sesuaikan dengan nama file yang kamu punya:
        // resources/views/user/reactions_index.blade.php
        return view('user.reactions_index', compact('posts'));
    }

    /**
     * Detail reaksi untuk satu postingan
     */
    public function showReactions(Post $post)
    {
        $post->load(['reactions.user']);

        return view('posts.reactions', compact('post'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use App\Notification\GeneralNotification;

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

        // Cek jika post sudah dihapus (soft delete)
        if ($post->trashed()) {
            return response()->json(['message' => 'Postingan tidak ditemukan atau telah dihapus.'], 404);
        }

        $type = $request->input('type'); // 'like', 'dislike', 'remove'
        if (!in_array($type, ['like', 'dislike', 'remove'])) {
            return response()->json(['message' => 'Tipe tidak valid'], 422);
        }

        try {
            // query reaction user untuk post ini
            $query = Reaction::where('post_id', $post->id)
                             ->where('user_id', $user->id);
            $existing = $query->first();

            if ($type === 'remove') {
                $query->delete();
            } else {
                $value = $type === 'like' ? 1 : -1;

                if ($existing && $existing->reaction == $value) {
                    // klik ulang reaksi yang sama -> toggle off
                    $query->delete();
                } else {
                    $query->delete();

                    Reaction::create([
                        'post_id'  => $post->id,
                        'user_id'  => $user->id,
                        'reaction' => $value,
                    ]);

                    // kirim notifikasi ke pemilik post
                    $postOwner = $post->user;
                    if ($postOwner && $user->id !== $postOwner->id) {
                        $notifType = $type; 
                        $notifMsg = "{$user->name} " . ($type === 'like'
                                    ? 'menyukai'
                                    : 'tidak menyukai') . " postingan Anda berjudul: {$post->title}";
                        $extra = [
                            'post_id' => $post->id,
                            'reactor_id' => $user->id
                        ];
                        $postOwner->notify(new GeneralNotification($notifType, $notifMsg, $extra));
                    }
                }
            }

            // hitung ulang
            $likes    = Reaction::where('post_id', $post->id)->where('reaction', 1)->count();
            $dislikes = Reaction::where('post_id', $post->id)->where('reaction', -1)->count();

            $current = Reaction::where('post_id', $post->id)
                ->where('user_id', $user->id)
                ->first();

            return response()->json([
                'likes'         => $likes,
                'dislikes'      => $dislikes,
                'user_reaction' => $current ? $current->reaction : 0,
            ], 200);

        } catch (\Throwable $e) {
            \Log::error('React error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memproses reaksi'], 500);
        }
    }

    /**
     * Halaman "Daftar Suka"
     */
    public function myPostReactions()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $posts = Post::with([
                'reactions' => function ($q) {
                    $q->orderByDesc('updated_at');
                },
                'reactions.user'
            ])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.reactions_index', compact('posts'));
    }

    /**
     * Detail reaksi untuk satu postingan
     */
    public function showReactions(Post $post)
    {
        // Cek jika post sudah dihapus (soft delete)
        if ($post->trashed()) {
            abort(404, 'Postingan tidak ditemukan atau telah dihapus.');
        }

        // SIMPAN URL untuk tombol kembali
        session(['reaction_detail_back_url' => url()->previous()]);

        $post->load(['reactions.user']);

        return view('posts.reactions', compact('post'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\ActivityLog; // <<< TAMBAH INI
use Illuminate\Http\Request;
use Carbon\Carbon;

class CommentController extends Controller
{
    public function __construct()
    {
        // pastikan hanya user login yang bisa melakukan CRUD komentar
        $this->middleware('auth')->except('index');
    }

    /**
     * Store a newly created comment or reply.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id'   => 'required|exists:posts,id',
            'body'      => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Jika ini reply, pastikan parent comment berada di post yang sama
        if ($request->filled('parent_id')) {
            $parent = Comment::find($request->input('parent_id'));
            if (!$parent || $parent->post_id != $request->input('post_id')) {
                return back()->with('error', 'Komentar induk tidak valid untuk postingan ini.');
            }
        }

        $data = $request->only('post_id', 'body', 'parent_id');
        $data['user_id'] = $request->user()->id;

        $comment = Comment::create($data);

        // === ACTIVITY LOG: comment.created ===
        ActivityLog::record(
            action: 'comment.created',
            description: 'User menambahkan komentar baru',
            context: 'comment',
            detail: [
                'comment_id' => $comment->id,
                'post_id'    => $comment->post_id,
                'body'       => $comment->body,
                'parent_id'  => $comment->parent_id,
            ]
        );

        // Kirim notifikasi ke pemilik post (asal bukan dirinya sendiri)
        $post  = $comment->post;
        $owner = $post->user;
        if ($owner && $owner->id !== $request->user()->id) {
            $owner->notify(new \App\Notification\GeneralNotification(
                'post_commented',
                "Postingan Anda berjudul '{$post->title}': telah dikomentari oleh " .
                $request->user()->name,
                ["post_id" => $post->id]
            ));
        }

        // Jika request dari AJAX
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Komentar berhasil ditambahkan.',
                'comment' => $comment->load('user')
            ], 201);
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Update an existing comment (only by its owner within 1 minute).
     */
    public function update(Request $request, Comment $comment)
    {
        // hanya pemilik komentar yang boleh edit
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // batas waktu edit: 1 menit setelah dibuat
        if ($comment->created_at->lt(now()->subMinute())) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Batas waktu edit telah lewat (1 menit).'], 422);
            }
            return back()->with('error', 'Batas waktu edit telah lewat (1 menit).');
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update(['body' => $data['body']]);

        // ambil perubahan yang terjadi (akan ada body & updated_at)
        $changes = $comment->getChanges();

        // === ACTIVITY LOG: comment.updated ===
        ActivityLog::record(
            action: 'comment.updated',
            description: 'User mengubah komentar',
            context: 'comment',
            detail: [
                'comment_id' => $comment->id,
                'post_id'    => $comment->post_id,
                'changes'    => $changes,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Komentar berhasil diperbarui.',
                'comment' => $comment->fresh()->load('user')
            ], 200);
        }

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }

    /**
     * Remove a comment (allowed for comment owner or post owner).
     */
    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        // hanya pemilik komentar atau pemilik posting yang bisa hapus
        if ($user->id !== $comment->user_id && $user->id !== $comment->post->user_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // simpan dulu info penting sebelum dihapus
        $commentId = $comment->id;
        $postId    = $comment->post_id;

        $comment->delete();

        // === ACTIVITY LOG: comment.deleted ===
        ActivityLog::record(
            action: 'comment.deleted',
            description: 'User menghapus komentar',
            context: 'comment',
            detail: [
                'comment_id' => $commentId,
                'post_id'    => $postId,
            ]
        );

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Komentar dihapus.'], 200);
        }

        return back()->with('success', 'Komentar dihapus.');
    }
}

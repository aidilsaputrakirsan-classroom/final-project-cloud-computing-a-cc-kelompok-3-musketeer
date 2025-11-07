<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | Chatter Box</title>
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 7px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }

        /* tombol atas */
        .top-controls {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 16px;
        }
        .top-controls .btn {
            padding: 8px 14px;
            border-radius: 7px;
            font-size: 0.95em;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            background: #6c757d;
            color: #fff;
            transition: background 0.2s;
        }
        .top-controls .btn:hover {
            background: #5a6268;
        }

        .post-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .post-author-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #40A09C;
        }
        .author-name {
            font-weight: 600;
            color: #d49d3d;
            font-size: 1em;
        }
        .post-meta {
            font-size: 0.9em;
            color: #999;
            margin-bottom: 15px;
        }
        .post-title {
            font-weight: 600;
            font-size: 1.5em;
            color: #4b5d6b;
            margin-bottom: 15px;
        }
        .post-content {
            font-size: 1.05em;
            color: #314057;
            line-height: 1.6;
            margin-bottom: 20px;
            white-space: pre-wrap;
        }
        .post-stats {
            display: flex;
            gap: 20px;
            font-size: 1em;
            color: #787878;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 7px;
        }
        .post-categories {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }
        .post-category-badge {
            padding: 5px 14px;
            background: #40A09C;
            color: #fff;
            border-radius: 5px;
            font-size: 0.91em;
        }
        .post-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 7px;
            font-size: 0.97em;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            border: none;
            transition: background 0.2s;
        }
        .btn-primary {
            background: #40A09C;
            color: #fff;
        }
        .btn-primary:hover {
            background: #278a84;
        }
        .btn-danger {
            background: #dc3545;
            color: #fff;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-secondary {
            background: #6c757d;
            color:#fff;
        }
        .btn-secondary:hover {
            background:#5a6268;
        }

        /* komentar styles */
        .comments-section { margin-top: 24px; }
        .comment-form textarea { width: 100%; padding: .6rem; border:1px solid #e6e6e6; border-radius:6px; resize:vertical; }
        .comment-item { padding: .75rem 0; border-bottom: 1px solid #f0f0f0; }
        .comment-meta strong { color: #2c3e50; }
        .comment-body { white-space: pre-wrap; color: #333; margin-top: .4rem; margin-bottom: .4rem; }
        .comments-empty { color:#666; padding:.6rem 0; }
        .small-muted { color:#777; font-size:.9em; }

        /* flash */
        .flash-success { background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin-bottom:12px; }
        .flash-error { background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin-bottom:12px; }

        /* replies styling */
        .replies { margin-top:12px; padding-left:18px; border-left:1px solid #eee; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>
<div class="container">

    {{-- flash messages --}}
    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    {{-- tombol kembali di atas --}}
    <div class="top-controls">
        <a href="{{ route('dashboard') }}" class="btn">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="post-header">
        <div class="post-author-row">
            <div class="author-avatar"></div>
            <div class="author-name">{{ $post->user->name }}</div>
        </div>
        <div class="post-meta">
            Dipublikasikan pada {{ $post->created_at->format('d F Y, H:i') }}
            @if($post->updated_at != $post->created_at)
                • Diperbarui pada {{ $post->updated_at->format('d F Y, H:i') }}
            @endif
        </div>
        <div class="post-title">{{ $post->title }}</div>
    </div>

    <div class="post-content">{{ $post->content }}</div>

    <div class="post-stats">
        <span><i class="fa fa-eye"></i> {{ $post->views ?? 0 }} Dilihat</span>
        <span><i class="fa fa-comment"></i> {{ $post->comments_count ?? $post->comments->count() }} Komentar</span>
        <span><i class="fa fa-thumbs-up"></i> {{ $post->likes ?? 0 }} Suka</span>
        <span><i class="fa fa-thumbs-down"></i> {{ $post->dislikes ?? 0 }} Tidak Suka</span>
    </div>

    @if($post->category)
        <div class="post-categories">
            <span class="post-category-badge">{{ $post->category }}</span>
        </div>
    @endif

    <div class="post-actions">
        @if(Auth::id() === $post->user_id)
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">
                <i class="fa fa-edit"></i> Edit Postingan
            </a>
            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                    <i class="fa fa-trash"></i> Hapus Postingan
                </button>
            </form>
        @endif
    </div>

    {{-- Komentar Section --}}
    <div id="comments" class="comments-section">
        <h3>Komentar ({{ $post->comments_count ?? $post->comments->count() }})</h3>

        {{-- Form komentar (top-level) --}}
        <div class="comment-form" style="margin: 12px 0 18px;">
            @auth
                <form id="comment-form" action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <textarea name="body" id="comment-body" rows="4" placeholder="Tulis komentar..." required>{{ old('body') }}</textarea>
                    @error('body')
                        <div class="small-muted" style="color:#e3342f;margin-top:6px;">{{ $message }}</div>
                    @enderror
                    <div style="margin-top:8px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i> Kirim
                        </button>
                    </div>
                </form>
            @else
                <p class="small-muted">Silakan <a href="{{ route('login') }}">login</a> untuk mengomentari.</p>
            @endauth
        </div>

        {{-- List komentar (top-level) --}}
        <div id="comments-list">
            @php
                // gunakan $comments dari controller kalau ada; jika tidak, ambil top-level di view
                if (!isset($comments)) {
                    $comments = $post->comments()->whereNull('parent_id')->with('user','children.user')->latest()->get();
                }
            @endphp

            @forelse($comments as $comment)
                <div id="comment-{{ $comment->id }}" class="comment-item">
                    <p class="comment-meta" style="margin:0;">
                        <strong>{{ $comment->user->name }}</strong>
                        <span class="small-muted"> · {{ $comment->created_at->diffForHumans() }}</span>
                    </p>

                    {{-- tampilkan body --}}
                    <div class="comment-body" data-original-body>{{ $comment->body }}</div>

                    {{-- actions: edit (owner only, allowed within 1 minute) + delete + reply --}}
                    @auth
                        @php
                            $isOwner = auth()->id() === $comment->user_id;
                            $isPostOwner = auth()->id() === $post->user_id;
                            $canEdit = $isOwner && $comment->created_at->greaterThan(now()->subMinute());
                        @endphp

                        <div style="margin-top:6px;">
                            @if($isOwner)
                                <button type="button"
                                        class="btn btn-secondary btn-comment-edit"
                                        data-comment-id="{{ $comment->id }}"
                                        @if(!$canEdit) disabled title="Waktu edit sudah lewat (1 menit)" @endif
                                        style="padding:6px 10px;font-size:.9em;">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                            @endif

                            @if($isOwner || $isPostOwner)
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;margin-left:6px;" onsubmit="return confirm('Hapus komentar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:.9em;">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif

                            {{-- Reply button (any authenticated user) --}}
                            <button type="button" class="btn btn-primary btn-reply-toggle" data-comment-id="{{ $comment->id }}" style="padding:6px 10px;font-size:.9em;margin-left:6px;">
                                <i class="fa fa-reply"></i> Balas
                            </button>
                        </div>

                        {{-- Inline edit form (hidden) --}}
                        @if($isOwner)
                            <div class="comment-edit-form-wrap" id="comment-edit-form-{{ $comment->id }}" style="display:none;margin-top:10px;">
                                <form action="{{ route('comments.update', $comment) }}" method="POST" class="comment-edit-form">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="body" rows="3" style="width:100%;padding:.5rem;border:1px solid #ddd;border-radius:5px;">{{ $comment->body }}</textarea>
                                    <div style="margin-top:6px;">
                                        <button type="submit" class="btn btn-primary" style="padding:6px 10px;font-size:.9em;">Simpan</button>
                                        <button type="button" class="btn btn-secondary btn-edit-cancel" data-comment-id="{{ $comment->id }}" style="padding:6px 10px;font-size:.9em;margin-left:6px;">Batal</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Reply form (hidden) --}}
                        <div class="comment-reply-wrap" id="comment-reply-{{ $comment->id }}" style="display:none;margin-top:10px;">
                            <form action="{{ route('comments.store') }}" method="POST" class="comment-reply-form">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <textarea name="body" rows="2" placeholder="Tulis balasan..." style="width:100%;padding:.45rem;border:1px solid #ddd;border-radius:5px;"></textarea>
                                <div style="margin-top:6px;">
                                    <button type="submit" class="btn btn-primary" style="padding:6px 10px;font-size:.9em;">Kirim</button>
                                    <button type="button" class="btn btn-secondary btn-reply-cancel" data-comment-id="{{ $comment->id }}" style="padding:6px 10px;font-size:.9em;margin-left:6px;">Batal</button>
                                </div>
                            </form>
                        </div>
                    @endauth

                    {{-- Tampilkan replies (children) --}}
                    @if($comment->relationLoaded('children') ? $comment->children->isNotEmpty() : $comment->children()->exists())
                        <div class="replies">
                            @foreach($comment->children as $reply)
                                <div id="comment-{{ $reply->id }}" style="margin-bottom:12px;">
                                    <p style="margin:0;">
                                        <strong>{{ $reply->user->name }}</strong>
                                        <span class="small-muted"> · {{ $reply->created_at->diffForHumans() }}</span>
                                    </p>
                                    <div class="comment-body">{{ $reply->body }}</div>

                                    @auth
                                        @php
                                            $isReplyOwner = auth()->id() === $reply->user_id;
                                            $canEditReply = $isReplyOwner && $reply->created_at->greaterThan(now()->subMinute());
                                        @endphp
                                        <div style="margin-top:6px;">
                                            @if($isReplyOwner)
                                                <button type="button" class="btn btn-secondary btn-comment-edit" data-comment-id="{{ $reply->id }}" @if(!$canEditReply) disabled title="Waktu edit sudah lewat (1 menit)" @endif style="padding:6px 10px;font-size:.9em;">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                            @endif

                                            @if($isReplyOwner || auth()->id() === $post->user_id)
                                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" style="display:inline;margin-left:6px;" onsubmit="return confirm('Hapus komentar ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:.9em;">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        {{-- Inline edit form for reply --}}
                                        @if($isReplyOwner)
                                            <div class="comment-edit-form-wrap" id="comment-edit-form-{{ $reply->id }}" style="display:none;margin-top:10px;">
                                                <form action="{{ route('comments.update', $reply) }}" method="POST" class="comment-edit-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <textarea name="body" rows="2" style="width:100%;padding:.45rem;border:1px solid #ddd;border-radius:5px;">{{ $reply->body }}</textarea>
                                                    <div style="margin-top:6px;">
                                                        <button type="submit" class="btn btn-primary" style="padding:6px 10px;font-size:.9em;">Simpan</button>
                                                        <button type="button" class="btn btn-secondary btn-edit-cancel" data-comment-id="{{ $reply->id }}" style="padding:6px 10px;font-size:.9em;margin-left:6px;">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="comments-empty">Belum ada komentar. Jadilah yang pertama!</p>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle edit form
    document.querySelectorAll('.btn-comment-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            const id = this.dataset.commentId;
            const wrap = document.getElementById('comment-edit-form-' + id);
            if (!wrap) return;
            wrap.style.display = wrap.style.display === 'none' ? 'block' : 'none';
            const ta = wrap.querySelector('textarea');
            if (ta) ta.focus();
        });
    });

    // Cancel edit
    document.querySelectorAll('.btn-edit-cancel').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.commentId;
            const wrap = document.getElementById('comment-edit-form-' + id);
            if (wrap) wrap.style.display = 'none';
        });
    });

    // Toggle reply forms
    document.querySelectorAll('.btn-reply-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.commentId;
            const wrap = document.getElementById('comment-reply-' + id);
            if (!wrap) return;
            wrap.style.display = wrap.style.display === 'none' ? 'block' : 'none';
            const ta = wrap.querySelector('textarea');
            if (ta) ta.focus();
        });
    });

    // Cancel reply
    document.querySelectorAll('.btn-reply-cancel').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.commentId;
            const wrap = document.getElementById('comment-reply-' + id);
            if (wrap) wrap.style.display = 'none';
        });
    });
});
</script>
</body>
</html>

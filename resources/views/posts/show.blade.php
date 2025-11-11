@extends('layouts.main')

@section('title', $post->title . ' | Chatter Box')

@section('content')
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .post-content {
            padding-left: 20px !important;
        }

        .post-container {
            max-width: none;
            width: 95%;
            margin: 0;
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
            padding-bottom:
            margin-bottom: 10px;
        }
        .post-author-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
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
            margin-bottom: 10px;
        }
        .post-title {
            font-weight: 600;
            font-size: 1.5em;
            color: #4b5d6b;
            margin-bottom: 6px; 
        }
        .post-content {
            font-size: 1.05em;
            color: #314057;
            line-height: 1.6;
            margin-top: 0;
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
            text-decoration: none;
            display: inline-block;
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

        /* responsive tweak */
        @media (max-width: 640px) {
            .post-container { padding: 18px; }
            .post-title { font-size: 1.25em; }
        }
    </style>

    <div class="post-container" id="post-show-container">

        {{-- flash messages --}}
        @if(session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-error">{{ session('error') }}</div>
        @endif

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
                {{-- kategori sekarang link ke categories.show --}}
                <a href="{{ route('categories.show', $post->category->slug) }}"
                   class="post-category-badge"
                   title="Lihat semua postingan di kategori {{ $post->category->name }}">
                    {{ $post->category->name }}
                </a>
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

        {{-- Partial komentar --}}
        @include('partials.comments', ['post' => $post, 'comments' => $comments ?? null])

    </div>
@endsection

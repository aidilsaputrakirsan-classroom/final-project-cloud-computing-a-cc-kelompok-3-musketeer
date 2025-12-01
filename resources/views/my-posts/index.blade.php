@extends('layouts.main')

@section('title', 'Postingan Saya | Chatter Box')

@section('content')
@php
    // pastikan $user sudah dikirim dari controller,
    // kalau belum, ini fallback aman:
    $user = $user ?? auth()->user();
@endphp

<style>
    .profile-header {
        background: #fff;
        border-radius: 7px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        padding: 30px;
        margin-bottom: 25px;
    }
    .profile-info {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
        width: 100%;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #40A09C;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 2em;
        font-weight: bold;
        background-size: cover;
        background-position: center;
    }
    .profile-details h1 {
        margin: 0 0 5px 0;
        color: #4b5d6b;
        font-size: 1.5em;
    }
    .profile-details p {
        margin: 0;
        color: #787878;
        font-size: 0.95em;
    }
    .profile-stats {
        display: flex;
        gap: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    .stat-item { text-align: center; }
    .stat-value {
        font-size: 1.5em;
        font-weight: bold;
        color: #40A09C;
        display: block;
    }
    .stat-label {
        font-size: 0.9em;
        color: #787878;
        margin-top: 5px;
    }
    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .btn-post {
        background: #40A09C;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 7px;
        font-size: 0.97em;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
    }
    .btn-post:hover { background: #278a84; }
    .alert {
        padding: 12px 16px;
        border-radius: 7px;
        margin-bottom: 20px;
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .cards-list { display: flex; flex-direction: column; gap: 5px; margin-top: 12px; }
    .post-card {
        background: #fff;
        border-radius: 7px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        padding: 19px 15px 11px 15px;
        min-width: 0;
    }
    .post-meta { font-size: 0.90em; color: #999; margin-bottom: 4px; }
    .post-title {
        font-weight: 600;
        font-size: 1.09em;
        margin-bottom: 4px;
        color: #4b5d6b;
    }
    .post-title a { text-decoration: none; color: inherit; }
    .post-title a:hover { color: #40A09C; }
    .post-desc {
        font-size: 0.98em;
        color: #314057;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .post-stats {
        display: flex;
        gap: 16px;
        font-size: 0.95em;
        color: #787878;
        margin-bottom: 7px;
        flex-wrap: wrap;
        align-items: center;
    }
    .post-categories { display: flex; gap: 8px; margin-bottom: 3px; }
    .post-category-badge {
        padding: 3px 14px;
        background: #40A09C;
        color: #fff;
        border-radius: 5px;
        font-size: 0.91em;
    }
    .post-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #eee;
    }
    .btn-edit, .btn-delete {
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 0.9em;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        border: none;
    }
    .btn-edit { background: #40A09C; color: #fff; }
    .btn-edit:hover { background: #278a84; }
    .btn-delete { background: #dc3545; color: #fff; }
    .btn-delete:hover { background: #c82333; }
    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 30px;
    }

    /* clickable description & comment icon */
    .post-desc a {
        color: inherit;
        text-decoration: none;
        display: block;
    }
    .post-stats a {
        color: inherit;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="profile-header">
    <div class="profile-info">
        <div class="profile-avatar"
             style="@if($user && $user->profile_picture) background-image: url('{{ Storage::url($user->profile_picture) }}'); @endif">
            @if(!$user || !$user->profile_picture)
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            @endif
        </div>
        <div class="profile-details">
            <h1>{{ $user->name ?? 'User' }}</h1>
            <p>{{ $user->email ?? '' }}</p>
        </div>
        <div style="margin-left: auto;">
            <a href="{{ route('profile.edit') }}" style="padding: 8px 16px; background: #40A09C; color: #fff; border-radius: 7px; text-decoration: none; font-size: 0.9em;">
                <i class="fa fa-edit"></i> Edit Profile
            </a>
        </div>
    </div>
    <div class="profile-stats">
        <div class="stat-item">
            <span class="stat-value">{{ $user?->posts()->count() ?? 0 }}</span>
            <span class="stat-label">Total Postingan</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $user?->posts()->sum('views') ?? 0 }}</span>
            <span class="stat-label">Total Views</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $user?->posts()->sum('likes') ?? 0 }}</span>
            <span class="stat-label">Total Likes</span>
        </div>
    </div>
</div>

<div class="dashboard-header">
    <h1 style="margin: 0; color: #4b5d6b;">Postingan Saya</h1>
    <a href="{{ route('posts.create') }}" class="btn-post">
        <i class="fa fa-plus"></i> Buat Postingan Baru
    </a>
</div>

@if(session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<div class="cards-list">
    @forelse($posts as $post)
        {{-- penting: data-post-id untuk dipakai JS like/dislike global --}}
        <div class="post-card" data-post-id="{{ $post->id }}">
            <div class="post-meta">{{ $post->created_at->format('d M Y, H:i') }}</div>
            <div class="post-title">
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
            </div>

            {{-- Deskripsi yang bisa diklik --}}
            <div class="post-desc">
                <a href="{{ route('posts.show', $post) }}">
                    {{ $post->content }}
                </a>
            </div>

            @php
                try {
                    $likesCount = method_exists($post, 'likes') ? $post->likes()->count() : ($post->likes ?? 0);
                    $dislikesCount = method_exists($post, 'dislikes') ? $post->dislikes()->count() : ($post->dislikes ?? 0);
                } catch (\Throwable $e) {
                    $likesCount = $post->likes ?? 0;
                    $dislikesCount = $post->dislikes ?? 0;
                }

                $userReaction = null;
                if (Auth::check()) {
                    try {
                        $r = $post->reactions()->where('user_id', Auth::id())->first();
                        $userReaction = $r ? $r->reaction : null;
                    } catch (\Throwable $e) {
                        $userReaction = null;
                    }
                }
            @endphp

            <div class="post-stats">
                <span><i class="fa fa-eye"></i> {{ $post->views }}</span>

                <span>
                    <a href="{{ route('posts.show', $post) }}">
                        <i class="fa fa-comment"></i> {{ $post->comments_count }}
                    </a>
                </span>

                @if(Auth::check())
                    {{-- tombol LIKE / DISLIKE interaktif, sama pola dengan dashboard --}}
                    <button
                        type="button"
                        class="reaction-btn like-btn {{ $userReaction == 1 ? 'active' : '' }}"
                        data-post-id="{{ $post->id }}"
                        title="Suka"
                    >
                        <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        <span class="likes-count">{{ $likesCount }}</span>
                    </button>

                    <button
                        type="button"
                        class="reaction-btn dislike-btn {{ $userReaction == -1 ? 'active' : '' }}"
                        data-post-id="{{ $post->id }}"
                        title="Tidak Suka"
                    >
                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                        <span class="dislikes-count">{{ $dislikesCount }}</span>
                    </button>
                @else
                    {{-- Kalau belum login, tampilkan angka saja --}}
                    <span style="display:inline-flex;align-items:center;gap:6px;color:#999;">
                        <i class="fa fa-thumbs-up"></i>
                        <span class="likes-count">{{ $likesCount }}</span>
                    </span>
                    <span style="display:inline-flex;align-items:center;gap:6px;color:#999;">
                        <i class="fa fa-thumbs-down"></i>
                        <span class="dislikes-count">{{ $dislikesCount }}</span>
                    </span>
                @endif
            </div>

            @if($post->category)
                <div class="post-categories">
                    <span class="post-category-badge">
                        {{ $post->category->name }}
                    </span>
                </div>
            @endif

            <div class="post-actions">
                <a href="{{ route('posts.edit', $post) }}" class="btn-edit">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                        <i class="fa fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="post-card">
            <p style="text-align: center; color: #999; padding: 20px;">
                Belum ada postingan.
                <a href="{{ route('posts.create') }}" style="color: #40A09C;">Buat postingan pertama Anda!</a>
            </p>
        </div>
    @endforelse
</div>

@if($posts->hasPages())
    <div class="pagination">
        {{ $posts->links() }}
    </div>
@endif
@endsection

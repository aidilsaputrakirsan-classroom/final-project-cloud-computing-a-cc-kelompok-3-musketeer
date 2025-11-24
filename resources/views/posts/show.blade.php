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
        .post-container {
            max-width: none;
            width: 95%;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 7px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
            position: relative;
        }
        .post-header {
            margin-bottom: 14px;
        }
        .post-title {
            font-weight: 700;
            font-size: 1.6em;
            margin: 8px 0;
            color: #2b3d4f;
        }
        .post-content {
            color: #333;
            margin-bottom: 12px;
            white-space: pre-wrap;
        }
        .post-stats {
            display: flex;
            gap: 12px;
            align-items: center;
            color: #666;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .report-btn-top {
            position: absolute;
            top: 16px;
            right: 16px;
            background: #f44336;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
            font-size: 0.85em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        #report-modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        #report-modal .modal-content {
            background: #fff;
            padding: 18px 20px;
            border-radius: 8px;
            width: 360px;
            max-width: 90%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
    </style>

    <div class="post-container" data-post-id="{{ $post->id }}">
        {{-- report button (non-owner) --}}
        @if(Auth::check() && Auth::id() !== $post->user_id)
            <button class="report-btn-top" title="Laporkan"
                    onclick="document.getElementById('report-modal').style.display='flex'">
                <i class="fa fa-flag"></i> Laporkan
            </button>
        @endif

        {{-- flash messages --}}
        @if(session('success'))
            <div style="padding:12px 16px;border-radius:7px;margin-bottom:20px;background:#d4edda;color:#155724;border:1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="padding:12px 16px;border-radius:7px;margin-bottom:20px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        <div class="post-header">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#ddd;"></div>
                <div>
                    <div style="font-weight:600;color:#d49d3d;">{{ $post->user->name }}</div>
                    <div style="font-size:0.9em;color:#888;">{{ $post->created_at->format('d F Y, H:i') }}</div>
                </div>
            </div>
            <div class="post-title">{{ $post->title }}</div>
        </div>

        <div class="post-content">{{ $post->content }}</div>

        {{-- POST STATS + REACTION BUTTONS --}}
        <div class="post-stats">
            <span><i class="fa fa-eye"></i> {{ $post->views ?? 0 }} Dilihat</span>
            <span><i class="fa fa-comment"></i> {{ $post->comments_count ?? $post->comments->count() }} Komentar</span>

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

            @if(Auth::check())
                <div style="display:inline-flex;gap:10px;align-items:center;">
                    {{-- LIKE --}}
                    <button
                        class="reaction-btn like-btn {{ $userReaction == 1 ? 'active' : '' }}"
                        data-post-id="{{ $post->id }}"
                        type="button"
                        title="Suka"
                    >
                        <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        <span class="likes-count">{{ $likesCount }}</span>
                    </button>

                    {{-- DISLIKE --}}
                    <button
                        class="reaction-btn dislike-btn {{ $userReaction == -1 ? 'active' : '' }}"
                        data-post-id="{{ $post->id }}"
                        type="button"
                        title="Tidak Suka"
                    >
                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                        <span class="dislikes-count">{{ $dislikesCount }}</span>
                    </button>
                </div>
            @else
                {{-- Non-auth users: tampilkan angka dengan kelas agar konsisten --}}
                <div style="color:#999;display:inline-flex;gap:12px;align-items:center;">
                    <i class="fa fa-thumbs-up"></i>
                    <span class="likes-count">{{ $likesCount }}</span>
                </div>
                <div style="color:#999;display:inline-flex;gap:12px;align-items:center;">
                    <i class="fa fa-thumbs-down"></i>
                    <span class="dislikes-count">{{ $dislikesCount }}</span>
                </div>
            @endif
        </div>

        @if($post->category)
            <div class="post-categories" style="margin-bottom:12px;">
                <a href="{{ route('categories.show', $post->category->slug) }}"
                   class="post-category-badge"
                   style="display:inline-block;background:#40A09C;color:#fff;padding:5px 14px;border-radius:20px;text-decoration:none;">
                    {{ $post->category->name }}
                </a>
            </div>
        @endif

        {{-- tombol aksi --}}
        <div class="post-actions" style="margin-top:8px;">
            @if(Auth::check() && Auth::id() === $post->user_id)
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary" style="display:inline-block;margin-right:8px;">
                    <i class="fa fa-edit"></i> Edit Postingan
                </a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger"
                            style="background:#dc3545;color:#fff;padding:6px 12px;border:none;border-radius:6px;cursor:pointer;"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                        <i class="fa fa-trash"></i> Hapus Postingan
                    </button>
                </form>
            @endif
        </div>

        {{-- partial komentar --}}
        @include('partials.comments', ['post' => $post, 'comments' => $comments ?? null])

        {{-- report modal --}}
        <div id="report-modal">
            <div class="modal-content">
                <h4 style="margin-bottom:10px;">Laporkan Postingan</h4>
                <form action="{{ route('reports.store', $post->id) }}" method="POST">
                    @csrf
                    <textarea name="reason" rows="4" placeholder="Alasan pelaporan..." style="width:100%;border:1px solid #ccc;border-radius:7px;padding:8px;"></textarea>
                    <div style="margin-top:10px;display:flex;gap:8px;justify-content:flex-end;">
                        <button type="button"
                                onclick="document.getElementById('report-modal').style.display='none'"
                                style="background:#ccc;padding:6px 12px;border:none;border-radius:6px;cursor:pointer;">
                            Batal
                        </button>
                        <button type="submit"
                                style="background:#40A09C;color:#fff;padding:6px 12px;border:none;border-radius:6px;cursor:pointer;">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

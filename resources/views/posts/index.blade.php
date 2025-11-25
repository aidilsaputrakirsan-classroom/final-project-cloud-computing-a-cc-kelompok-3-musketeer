@extends('layouts.main')

@section('title', 'Postingan | Chatter Box')

@section('content')
    <section class="dashboard-content" style="flex:1;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 22px;">
            <h1 style="margin:0;color:#4b5d6b;">Semua Postingan</h1>
            <a href="{{ route('posts.create') }}" style="background:#40A09C;color:#fff;padding:10px 20px;border-radius:7px;text-decoration:none;">+ Buat Postingan Baru</a>
        </div>

        <div class="cards-list" style="display:flex;flex-direction:column;gap:25px;margin-top:12px;padding:22px;">
            @forelse($posts as $post)
                {{-- wrapper per-post wajib punya data-post-id agar JS global dapat menemukan elemen terkait --}}
                <div class="post-card" data-post-id="{{ $post->id }}" style="background:#fff;border-radius:7px;box-shadow:0 1px 6px rgba(0,0,0,0.05);padding:19px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                        <div style="width:30px;height:30px;border-radius:50%;background:#ddd;"></div>
                        <div style="font-weight:600;color:#d49d3d;">{{ $post->user->name }}</div>
                    </div>

                    <div style="font-size:0.90em;color:#999;margin-bottom:4px;">{{ $post->created_at->format('d M Y, H:i') }}</div>

                    <div style="font-weight:600;font-size:1.09em;color:#4b5d6b;margin-bottom:6px;">
                        <a href="{{ route('posts.show', $post) }}" style="text-decoration:none;color:inherit;">{{ $post->title }}</a>
                    </div>

                    <div style="color:#314057;margin-bottom:10px;">{{ \Illuminate\Support\Str::limit($post->content, 150) }}</div>

                    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                        <span><i class="fa fa-eye"></i> {{ $post->views }}</span>
                        <span><i class="fa fa-comment"></i> {{ $post->comments_count }}</span>

                        @php
                            try {
                                $likesCount = method_exists($post,'likes') ? $post->likes()->count() : ($post->likes ?? 0);
                                $dislikesCount = method_exists($post,'dislikes') ? $post->dislikes()->count() : ($post->dislikes ?? 0);
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
                            {{-- LIKE button: gunakan type="button", data-post-id dan span.likes-count --}}
                            <button
                                type="button"
                                class="reaction-btn like-btn {{ $userReaction == 1 ? 'active' : '' }}"
                                data-post-id="{{ $post->id }}"
                                style="display:inline-flex;align-items:center;gap:8px;"
                                title="Suka"
                            >
                                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                <span class="likes-count">{{ $likesCount }}</span>
                            </button>

                            {{-- DISLIKE button --}}
                            <button
                                type="button"
                                class="reaction-btn dislike-btn {{ $userReaction == -1 ? 'active' : '' }}"
                                data-post-id="{{ $post->id }}"
                                style="display:inline-flex;align-items:center;gap:8px;"
                                title="Tidak Suka"
                            >
                                <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                <span class="dislikes-count">{{ $dislikesCount }}</span>
                            </button>
                        @else
                            {{-- Non-auth: tampilkan counts namun tetap gunakan kelas pada span agar JS bisa menemukan elemen (meskipun tombol tidak aktif) --}}
                            <div style="display:inline-flex;align-items:center;gap:8px;color:#999;">
                                <i class="fa fa-thumbs-up"></i>
                                <span class="likes-count">{{ $likesCount }}</span>
                            </div>
                            <div style="display:inline-flex;align-items:center;gap:8px;color:#999;">
                                <i class="fa fa-thumbs-down"></i>
                                <span class="dislikes-count">{{ $dislikesCount }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="post-card">
                    <p style="text-align:center;color:#999;padding:20px;">Belum ada postingan. <a href="{{ route('posts.create') }}" style="color:#40A09C;">Buat postingan pertama Anda!</a></p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div style="padding:22px;">{{ $posts->links() }}</div>
        @endif
    </section>
@endsection

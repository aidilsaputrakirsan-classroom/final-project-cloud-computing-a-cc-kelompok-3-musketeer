@extends('layouts.main')

@section('title', 'Postingan Saya | Chatter Box')

@section('content')
    <section class="dashboard-content" style="flex:1;">
        <div class="profile-header" style="background:#fff;border-radius:7px;box-shadow:0 1px 6px;padding:30px;margin-bottom:25px;">
            <div style="display:flex;align-items:center;gap:20px;">
                <div style="width:80px;height:80px;border-radius:50%;background:#40A09C;display:flex;align-items:center;justify-content:center;color:#fff;font-size:2em;font-weight:bold;">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <div>
                    <h1 style="margin:0;color:#4b5d6b;">{{ $user->name }}</h1>
                    <p style="margin:0;color:#787878;">{{ $user->email }}</p>
                </div>
            </div>

            <div style="display:flex;gap:30px;padding-top:20px;border-top:1px solid #eee;margin-top:10px;">
                <div>
                    <span style="font-weight:bold;color:#40A09C;">{{ $user->posts()->count() }}</span>
                    <div style="color:#787878;">Total Postingan</div>
                </div>
                <div>
                    <span style="font-weight:bold;color:#40A09C;">{{ $user->posts()->sum('views') }}</span>
                    <div style="color:#787878;">Total Views</div>
                </div>
                <div>
                    <span style="font-weight:bold;color:#40A09C;">{{ $user->posts()->sum('likes') }}</span>
                    <div style="color:#787878;">Total Likes</div>
                </div>
            </div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <h1 style="margin:0;color:#4b5d6b;">Postingan Saya</h1>
            <a href="{{ route('posts.create') }}" style="background:#40A09C;color:#fff;padding:10px 16px;border-radius:7px;text-decoration:none;">+ Buat Postingan Baru</a>
        </div>

        <div class="cards-list" style="display:flex;flex-direction:column;gap:20px;">
            @forelse($posts as $post)
                {{-- wrapper wajib punya data-post-id agar JS global dapat update elemen terkait --}}
                <div class="post-card" data-post-id="{{ $post->id }}" style="background:#fff;border-radius:7px;padding:19px;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
                    <div style="font-size:0.9em;color:#999;">{{ $post->created_at->format('d M Y, H:i') }}</div>

                    <div style="font-weight:600;font-size:1.09em;margin:6px 0;">
                        <a href="{{ route('posts.show', $post) }}" style="text-decoration:none;color:inherit;">{{ $post->title }}</a>
                    </div>

                    <div style="color:#314057;margin-bottom:8px;">{{ \Illuminate\Support\Str::limit($post->content, 300) }}</div>

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
                            {{-- LIKE button: pola wajib untuk JS global --}}
                            <button
                                type="button"
                                class="reaction-btn like-btn {{ $userReaction == 1 ? 'active' : '' }}"
                                data-post-id="{{ $post->id }}"
                                title="Suka"
                                style="display:inline-flex;align-items:center;gap:8px;"
                            >
                                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                <span class="likes-count">{{ $likesCount }}</span>
                            </button>

                            {{-- DISLIKE button --}}
                            <button
                                type="button"
                                class="reaction-btn dislike-btn {{ $userReaction == -1 ? 'active' : '' }}"
                                data-post-id="{{ $post->id }}"
                                title="Tidak Suka"
                                style="display:inline-flex;align-items:center;gap:8px;"
                            >
                                <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                <span class="dislikes-count">{{ $dislikesCount }}</span>
                            </button>
                        @else
                            {{-- Untuk user yang belum login: tampilkan counts (span tetap diberi kelas agar konsisten) --}}
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

                    <div style="margin-top:10px;">
                        <a href="{{ route('posts.edit', $post) }}" style="background:#40A09C;color:#fff;padding:6px 12px;border-radius:5px;text-decoration:none;"><i class="fa fa-edit"></i> Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:#dc3545;color:#fff;padding:6px 12px;border-radius:5px;border:none;cursor:pointer;" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')"><i class="fa fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="post-card">
                    <p style="text-align:center;color:#999;padding:20px;">Belum ada postingan. <a href="{{ route('posts.create') }}" style="color:#40A09C;">Buat postingan pertama Anda!</a></p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div style="padding-top:10px;">{{ $posts->links() }}</div>
        @endif
    </section>
@endsection

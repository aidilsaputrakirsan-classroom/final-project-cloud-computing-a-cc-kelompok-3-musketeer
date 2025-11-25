@extends('layouts.main')

@section('title', 'Dashboard | Chatter Box')

@section('content')
@php
    $user = auth()->user();
    // fallback jika controller belum mengirim
    $unreadCount = $unreadCount ?? 0;
    $notifications = $notifications ?? collect();
@endphp

<section class="dashboard-content" style="flex:1; padding:16px 30px 24px 0; min-width:0; margin-left:0;">

    {{-- HEADER --}}
    <div class="dashboard-header" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">

        <input type="text" class="search-bar"
               placeholder="Cari postingan ngetren saat ini?"
               style="width:270px; padding:7px 10px; border:1px solid #ddd; border-radius:7px; font-size:0.99em; background:#fff;">

        <div style="display:flex; align-items:center; gap:15px;">
            {{-- NOTIFICATION BELL --}}
            <div class="notification-wrapper" style="position:relative;">
                <div onclick="toggleNotif()" style="position:relative; cursor:pointer;">
                    <i class="fa fa-bell" style="font-size:1.25em; color:#555;"></i>

                    @if($unreadCount > 0)
                        <span style="position:absolute; top:-5px; right:-6px; background:red; color:white; font-size:0.7em; padding:2px 5px; border-radius:50%;">{{ $unreadCount }}</span>
                    @endif
                </div>

                <div id="notifDropdown" style="display:none; position:absolute; top:28px; right:0; width:320px; background:#fff; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.12); padding:8px 0; z-index:999;">
                    @forelse($notifications as $notif)
                        <div style="padding:10px 12px; border-bottom:1px solid #eee;">
                            <div style="font-size:0.92em; color:#333;">
                                @php
                                    $message = $notif->data['message'] ?? '';
                                    $parts = explode(':', $message, 2);
                                    $title = $parts[0] ?? '';
                                    $content = $parts[1] ?? '';
                                    $postId = $notif->data['post_id'] ?? null;
                                @endphp

                                @if(str_contains($message, 'telah dikomentari') && $postId)
                                    <a href="{{ route('posts.show', $postId) }}" style="color:#1d9bf0; text-decoration:none;">
                                        <span style="color:black; font-weight:600;">{{ $title }}</span>
                                        @if($content) : {{ $content }} @endif
                                    </a>
                                @else
                                    @if($title)
                                        <span style="color:black; font-weight:600;">{{ $title }}</span>
                                        @if($content) : {{ $content }} @endif
                                    @else
                                        {{ $message }}
                                    @endif
                                @endif
                            </div>
                            <div style="font-size:0.77em; color:#999; margin-top:6px;">
                                {{ $notif->created_at->diffForHumans() ?? '' }}
                            </div>
                        </div>
                    @empty
                        <div style="padding:12px; text-align:center; color:#777;">Tidak ada notifikasi</div>
                    @endforelse
                </div>
            </div>

            {{-- AVATAR --}}
            @if($user)
                <a href="{{ route('profile.edit') }}" style="text-decoration:none;">
                    <div class="user-avatar" style="width:32px; height:32px; border-radius:50%; background:#40A09C; background-size:cover; background-position:center; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:bold; font-size:0.9em; {{ $user->profile_picture ? "background-image:url('" . e(Storage::url($user->profile_picture)) . "');" : '' }}">
                        @unless($user->profile_picture)
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        @endunless
                    </div>
                </a>
            @endif

            {{-- LOGOUT --}}
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" title="Logout" style="background:#dc3545; color:#fff; border:none; border-radius:6px; padding:6px 10px; font-size:0.9em; cursor:pointer; display:flex; align-items:center; gap:5px;">
                    <i class="fa fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
    {{-- END HEADER --}}

    {{-- FILTER + BUTTON --}}
    <div class="content-list-controls" style="display:flex; align-items:center; gap:7px; margin-bottom:8px; margin-top:5px;">
        <button class="btn-filter" style="background:#e7f6fa; color:#40a09c; border:none; padding:6px 17px; border-radius:16px; font-size:0.97em; cursor:pointer;">Baru</button>
        <a href="{{ route('posts.create') }}" class="btn-post" style="background:#40A09C; color:#fff; border:none; padding:7px 17px; border-radius:7px; font-size:0.97em; font-weight:500; margin-left:auto; text-decoration:none; display:inline-block;">+ Buat Postingan</a>
    </div>

    {{-- FLASH --}}
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

    {{-- POST LIST --}}
    <div class="cards-list" style="display:flex; flex-direction:column; gap:12px; margin-top:12px;">
        @forelse($posts as $post)
            <div class="post-card" data-post-id="{{ $post->id }}" style="background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.05);padding:20px 22px;position:relative;">
                {{-- LAPOR --}}
                @if(Auth::check() && Auth::id() !== $post->user_id)
                    <button onclick="openReportModal({{ $post->id }})" style="position:absolute;top:15px;right:15px;padding:6px 12px;background:#f44336;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:0.85em;">
                        <i class="fa fa-flag"></i>
                    </button>
                @endif

                {{-- HEADER --}}
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                    <div style="width:40px; height:40px; border-radius:50%; background:#ddd; background-size:cover; background-position:center;"></div>
                    <div>
                        <div style="font-weight:600; color:#d49d3d; font-size:1em;">{{ $post->user->name }}</div>
                        <div style="font-size:0.85em; color:#888;">{{ $post->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                {{-- CONTENT --}}
                <div style="margin-top:5px; margin-bottom:8px;">
                    <a href="{{ route('posts.show', $post) }}" style="font-weight:700; font-size:1.05em; color:#2b3d4f; text-decoration:none; display:block; margin-bottom:4px;">
                        {{ $post->title }}
                    </a>
                    <p style="font-size:0.95em; color:#4a5568; margin:0 0 10px;">
                        <a href="{{ route('posts.show', $post) }}" style="color:inherit; text-decoration:none; display:block;">
                            {{ \Illuminate\Support\Str::limit($post->content, 150) }}
                        </a>
                    </p>
                </div>

                {{-- STATS + REACTIONS --}}
                <div style="display:flex; align-items:center; gap:16px; font-size:0.9em; color:#666; margin-bottom:10px; flex-wrap:wrap;">
                    <span><i class="fa fa-eye"></i> {{ $post->views }}</span>
                    <a href="{{ route('posts.show', $post) }}" style="color:inherit; text-decoration:none;"><i class="fa fa-comment"></i> {{ $post->comments_count }}</a>

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
                        <button type="button" class="reaction-btn like-btn {{ $userReaction == 1 ? 'active' : '' }}" data-post-id="{{ $post->id }}" style="display:inline-flex;align-items:center;gap:8px;" title="Suka">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                            <span class="likes-count">{{ $likesCount }}</span>
                        </button>

                        <button type="button" class="reaction-btn dislike-btn {{ $userReaction == -1 ? 'active' : '' }}" data-post-id="{{ $post->id }}" style="display:inline-flex;align-items:center;gap:8px;" title="Tidak Suka">
                            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                            <span class="dislikes-count">{{ $dislikesCount }}</span>
                        </button>
                    @else
                        <div style="display:inline-flex;align-items:center;gap:8px;color:#999;">
                            <i class="fa fa-thumbs-up"></i> <span class="likes-count">{{ $likesCount }}</span>
                        </div>
                        <div style="display:inline-flex;align-items:center;gap:8px;color:#999;">
                            <i class="fa fa-thumbs-down"></i> <span class="dislikes-count">{{ $dislikesCount }}</span>
                        </div>
                    @endif
                </div>

                {{-- CATEGORY --}}
                @if($post->category)
                    <div style="margin-bottom:12px;">
                        <a href="{{ route('categories.show', $post->category->slug) }}" style="display:inline-block;background:#40A09C;color:#fff;padding:5px 14px;border-radius:20px;text-decoration:none;">
                            {{ $post->category->name }}
                        </a>
                    </div>
                @endif

                {{-- EDIT/DELETE --}}
                @if(Auth::id() === $post->user_id)
                    <div style="display:flex; gap:10px; padding-top:10px; border-top:1px solid #eee;">
                        <a href="{{ route('posts.edit', $post) }}" style="padding:7px 15px; border-radius:6px; background:#40A09C; color:#fff; text-decoration:none; display:flex; align-items:center; gap:6px;">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')" style="padding:7px 15px; border-radius:6px; background:#dc3545; color:#fff; border:none; cursor:pointer; display:flex; align-items:center; gap:6px;">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="post-card" style="background:#fff; border-radius:7px; box-shadow:0 1px 6px; padding:20px; text-align:center;">
                <p style="color:#999;">
                    Belum ada postingan.
                    <a href="{{ route('posts.create') }}" style="color:#40A09C;">Buat postingan pertama Anda!</a>
                </p>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($posts->hasPages())
        <div style="display:flex; justify-content:center; margin-top:8px;">{{ $posts->links() }}</div>
    @endif

</section>

{{-- REPORT MODAL --}}
<div id="reportModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:1000;">
    <div style="background:#fff;padding:20px;border-radius:10px;width:320px;">
        <h3 style="margin-top:0;">Laporkan Postingan</h3>
        <form id="reportForm" method="POST">
            @csrf
            <label for="reason">Alasan:</label>
            <select name="reason" id="reason" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:5px;margin-top:5px;">
                <option value="">-- Pilih Alasan --</option>
                <option value="Konten tidak pantas">Konten tidak pantas</option>
                <option value="Spam atau penipuan">Spam atau penipuan</option>
                <option value="Ujaran kebencian">Ujaran kebencian</option>
                <option value="Pelecehan atau intimidasi">Pelecehan atau intimidasi</option>
                <option value="Informasi palsu">Informasi palsu</option>
            </select>

            <label for="details" style="margin-top:10px;display:block;">Detail (opsional):</label>
            <textarea name="details" id="details" rows="3" style="width:100%; border:1px solid #ddd; border-radius:5px; padding:8px; resize:none;"></textarea>

            <div style="margin-top:15px; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeReportModal()" style="background:#ccc;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;">Batal</button>
                <button type="submit" style="background:#f44336;color:#fff;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReportModal(postId) {
    const modal = document.getElementById("reportModal");
    const form = document.getElementById("reportForm");
    form.action = `/posts/${postId}/report`;
    modal.style.display = "flex";
}
function closeReportModal() {
    document.getElementById("reportModal").style.display = "none";
}
function toggleNotif() {
    const dropdown = document.getElementById('notifDropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
}
window.addEventListener('click', function(e) {
    const bell = document.querySelector('.notification-wrapper');
    const dropdown = document.getElementById('notifDropdown');
    if (!bell || !dropdown) return;
    if (!bell.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>

<style>
.post-card:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: 0.14s ease; }
</style>

@endsection

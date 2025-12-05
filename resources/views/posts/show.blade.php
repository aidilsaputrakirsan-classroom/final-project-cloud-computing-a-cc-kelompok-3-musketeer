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
    #reportModal {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    #reportModal .modal-content {
        background: #fff;
        padding: 18px 20px;
        border-radius: 8px;
        width: 360px;
        max-width: 90%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
</style>

<div class="post-container" data-post-id="{{ $post->id }}">
    
    {{-- REPORT BUTTON --}}
    @if(Auth::check() && Auth::id() !== $post->user_id)
        <button type="button"
            onclick="openReportModal({{ $post->id }})"
            style="position:absolute;top:15px;right:15px;
            padding:6px 14px;background:#f44336;color:#fff;
            border:none;border-radius:999px;cursor:pointer;
            font-size:0.85em;display:inline-flex;align-items:center;gap:6px;">
        <i class="fa fa-flag" aria-hidden="true"></i>
        <span>Laporkan</span>
        </button>
    @endif

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div style="padding:12px 16px;border-radius:7px;margin-bottom:20px;background:#d4edda;color:#155724;border:1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="padding:12px 16px;border-radius:7px;margin-bottom:20px;background:#f8d7da;color:#721c24;border:1px solid:#f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <div class="post-header">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
            <a href="{{ route('profile.show', $post->user) }}" style="text-decoration:none;">
                <div style="width:40px;height:40px;border-radius:50%;background:#40A09C;background-size:cover;background-position:center;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;font-size:1em;{{ $post->user->profile_picture ? "background-image:url('" . e(Storage::url($post->user->profile_picture)) . "');" : '' }}">
                    @unless($post->user->profile_picture)
                        {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                    @endunless
                </div>
            </a>
            <div>
                <a href="{{ route('profile.show', $post->user) }}" style="text-decoration:none; color:#d49d3d;">
                    <div style="font-weight:600;color:#d49d3d;">{{ $post->user->name }}</div>
                </a>
                <div style="font-size:0.9em;color:#888;">{{ $post->created_at->format('d F Y, H:i') }}</div>
            </div>
        </div>
        <div class="post-title">{{ $post->title }}</div>
    </div>

    <div class="post-content">{{ $post->content }}</div>

    {{-- STATS --}}
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
                <button class="reaction-btn like-btn {{ $userReaction == 1 ? 'active' : '' }}" data-post-id="{{ $post->id }}" type="button" title="Suka">
                    <i class="fa fa-thumbs-up"></i>
                    <span class="likes-count">{{ $likesCount }}</span>
                </button>

                <button class="reaction-btn dislike-btn {{ $userReaction == -1 ? 'active' : '' }}" data-post-id="{{ $post->id }}" type="button" title="Tidak Suka">
                    <i class="fa fa-thumbs-down"></i>
                    <span class="dislikes-count">{{ $dislikesCount }}</span>
                </button>
            </div>
        @else
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

    @php
        $editAllowed = now()->diffInMinutes($post->created_at) < 1;
    @endphp

    <div class="post-actions" style="margin-top:8px;">
        @if(Auth::check() && Auth::id() === $post->user_id)

            <a href="{{ $editAllowed ? route('posts.edit', $post) : '#' }}"
               style="display:inline-flex;align-items:center;gap:7px;
                    background: {{ $editAllowed ? '#40A09C' : '#bfbfbf' }};
                    color:#fff;border:none;
                    padding:10px 26px 10px 18px;border-radius:10px;
                    font-size:1.12em;font-weight:500;box-shadow:0 1px 2px rgba(50,80,80,0.07);
                    text-decoration:none;margin-right:12px;transition:background .18s;
                    cursor: {{ $editAllowed ? 'pointer' : 'not-allowed' }};"
               @if(!$editAllowed) onclick="return false;" @endif
            >
                <i class="fa fa-edit" style="font-size:1.22em;margin-right:7px;"></i> Edit
            </a>

            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="btn btn-danger"
                    style="background:#dc3545;color:#fff;padding:10px 22px;border:none;border-radius:9px;cursor:pointer;
                        font-size:1.08em;font-weight:500;display:inline-flex;align-items:center;gap:7px;"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                    <i class="fa fa-trash"></i> Hapus
                </button>
            </form>
        @endif
    </div>

    <style>
    a[style*="#40A09C"]:hover {
        background: #308880 !important;
        color: #fff !important;
    }
    </style>

    {{-- PARTIAL KOMENTAR --}}
    @include('partials.comments', ['post' => $post, 'comments' => $comments ?? null])

    {{-- REPORT MODAL --}}
    <div id="reportModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:1000;">
        <div class="modal-content" style="background:#fff;padding:20px;border-radius:10px;width:320px;">
            <h3 style="margin-top:0;">Laporkan Postingan</h3>
            <form id="reportForm" method="POST" action="{{ route('reports.store', $post->id) }}">
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
                <textarea name="details" id="details" rows="3" style="width:95%; border:1px solid #ddd; border-radius:5px; padding:8px; resize:none;"></textarea>
                <div style="margin-top:15px; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button"
                    onclick="closeReportModal()"
                    style="background:#ccc;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;">
                    Batal
                </button>
                <button type="submit" style="
                    background:#f44336;
                    color:#fff;
                    border:none;
                    padding:7px 10px;
                    border-radius:6px;
                    cursor:pointer;">
                    Kirim
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReportModal(postId) {
    // tampilkan modal
    document.getElementById("reportModal").style.display = "flex";

    // reset field
    document.getElementById("reason").value = "";
    document.getElementById("details").value = "";

    // ambil laporan sebelumnya (jika ada)
    fetch(`/reports/check/${postId}`)
        .then(res => res.json())
        .then(data => {
            if (data.exists) {
                // isi form dengan data existing
                document.getElementById("reason").value = data.report.reason;
                document.getElementById("details").value = data.report.details;
            }
        })
        .catch(err => console.error(err));
}

function closeReportModal() {
    document.getElementById("reportModal").style.display = "none";
}
</script>

@endsection

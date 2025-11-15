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
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        /* Tombol laporkan di pojok kanan atas */
        .report-btn-top {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #dc3545;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .report-btn-top:hover {
            color: #b02a37;
        }

        .post-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
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

        /* komentar styles */
        .comments-section {
            margin-top: 24px;
        }

        .comment-form textarea {
            width: 100%;
            padding: .6rem;
            border: 1px solid #e6e6e6;
            border-radius: 6px;
            resize: vertical;
        }

        .comment-item {
            padding: .75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .comment-meta strong {
            color: #2c3e50;
        }

        .comment-body {
            white-space: pre-wrap;
            color: #333;
            margin-top: .4rem;
            margin-bottom: .4rem;
        }

        .comments-empty {
            color: #666;
            padding: .6rem 0;
        }

        /* modal */
        #report-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        #report-modal .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            position: relative;
        }
    </style>

    <div class="post-container" id="post-show-container">

        {{-- Tombol laporkan di pojok kanan atas (tidak muncul untuk pemilik postingan) --}}
        @if(Auth::check() && Auth::id() !== $post->user_id)
            <button class="report-btn-top" title="Laporkan"
                onclick="document.getElementById('report-modal').style.display='flex'">
                <i class="fa fa-flag"></i>
            </button>
        @endif

        {{-- Tombol Laporkan di pojok kanan atas --}}
                    @if(Auth::check() && Auth::id() !== $post->user_id)
                        <button onclick="openReportModal({{ $post->id }})" style="
                            position:absolute;
                            top:15px;
                            right:15px;
                            padding:6px 12px;
                            background:#f44336;
                            color:#fff;
                            border:none;
                            border-radius:6px;
                            cursor:pointer;
                            font-size:0.85em;
                        ">
                            <i class="fa fa-flag"></i>
                        </button>
                    @endif

        {{-- flash messages --}}
        {{-- Pesan --}}
        @if(session('success'))
            <div style="padding:12px 16px;border-radius:7px;margin-bottom:20px;
                        background:#d4edda;color:#155724;border:1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="padding:12px 16px;border-radius:7px;margin-bottom:20px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;
                        background:#d4edda;color:#155724;border:1px solid #c3e6cb;"></div>">
                {{ session('error') }}
            </div>
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

        {{-- tombol aksi --}}
        <div class="post-actions">
            @if(Auth::check() && Auth::id() === $post->user_id)
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit Postingan
                </a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                        <i class="fa fa-trash"></i> Hapus Postingan
                    </button>
                </form>
            @endif
        </div>

        {{-- partial komentar --}}
        @include('partials.comments', ['post' => $post, 'comments' => $comments ?? null])

        {{-- modal laporan --}}
        <div id="report-modal">
            <div class="modal-content">
                <h4 style="margin-bottom:10px;">Laporkan Postingan</h4>
                <form action="{{ route('reports.store', $post->id) }}" method="POST">
                    @csrf
                    <textarea name="reason" rows="4" placeholder="Alasan pelaporan..."
                        style="width:100%;border:1px solid #ccc;border-radius:7px;padding:8px;"></textarea>
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

 {{-- Modal Laporan --}}
    <div id="reportModal" style="
        display:none;
        position:fixed;
        top:0; left:0;
        width:100%; height:100%;
        background:rgba(0,0,0,0.5);
        align-items:center;
        justify-content:center;
        z-index:1000;
    ">
        <div style="
            background:#fff;
            padding:20px;
            border-radius:10px;
            width:320px;
        ">
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
                <textarea 
                    name="details" 
                    id="details" 
                    rows="3" 
                    style="
                        width:100%;
                        border:1px solid #ddd;
                        border-radius:5px;
                        padding:8px;
                        resize: none;
                    "
                ></textarea>

                <div style="margin-top:15px;display:flex;justify-content:flex-end;gap:10px;">
                    <button type="button" onclick="closeReportModal()" style="background:#ccc;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;">Batal</button>
                    <button type="submit" style="background:#f44336;color:#fff;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReportModal(postId) {
            const modal = document.getElementById('reportModal');
            const form = document.getElementById('reportForm');
            form.action = `/posts/${postId}/report`;
            modal.style.display = 'flex';
        }

        function closeReportModal() {
            document.getElementById('reportModal').style.display = 'none';
        }

        window.onclick = function(e) {
            const modal = document.getElementById('reportModal');
            if (e.target === modal) {
                closeReportModal();
            }
        };
    </script>
@endsection

@extends('layouts.main')

@section('title', $category->name . ' | Chatter Box')

@section('content')
<div style="padding:24px; font-family:'Montserrat', Arial, sans-serif;">

    {{-- Header Kategori --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div>
            <h1 style="font-size:1.8rem;margin:0;">Kategori {{ $category->name }}</h1>
            <div style="color:#666;margin-top:6px;">
                Berikut postingan pada kategori {{ $category->name }}
            </div>
        </div>
        <div style="text-align:right;color:#888;">
            <div>{{ $posts->total() }} Postingan</div>
        </div>
    </div>

    {{-- NOTIFIKASI SUCCESS / ERROR --}}
    @if(session('success'))
        <div style="
            padding:12px 16px;
            border-radius:7px;
            margin-bottom:20px;
            background:#d4edda;
            color:#155724;
            border:1px solid #c3e6cb;
        ">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="
            padding:12px 16px;
            border-radius:7px;
            margin-bottom:20px;
            background:#f8d7da;
            color:#721c24;
            border:1px solid #f5c6cb;
        ">
            {{ session('error') }}
        </div>
    @endif

    {{-- LIST POSTINGAN --}}
    <div style="display:flex;flex-direction:column;gap:20px;margin-top:8px;">

        @forelse($posts as $post)
        <div class="post-card" style="
            background:#fff;
            border-radius:10px;
            box-shadow:0 2px 8px rgba(0,0,0,0.05);
            padding:18px 20px;
            position:relative;
        ">

                {{-- BUTTON LAPOR --}}
                @if(Auth::check() && Auth::id() !== $post->user_id)
                    <button onclick="openReportModal({{ $post->id }})"
                            style="position:absolute;top:15px;right:15px;padding:6px 12px;background:#f44336;
                                   color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:0.85em;">
                        <i class="fa fa-flag"></i>
                    </button>
                @endif

            {{-- Avatar dan User --}}
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                <div style="
                    width:40px;
                    height:40px;
                    border-radius:50%;
                    background:#ddd;
                "></div>

                <div>
                    <div style="font-weight:600;color:#d49d3d;font-size:1em;">
                        {{ $post->user->name }}
                    </div>
                    <div style="font-size:0.85em;color:#888;">
                        {{ $post->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            {{-- Judul & Konten --}}
            <a href="{{ route('posts.show', $post) }}" style="
                font-weight:700;
                font-size:1.05em;
                color:#2b3d4f;
                text-decoration:none;
                display:block;
                margin-bottom:6px;
            ">
                {{ $post->title }}
            </a>

            <p style="font-size:0.95em;color:#4a5568;margin:0 0 10px;">
                {{ \Illuminate\Support\Str::limit($post->content, 150) }}
            </p>

            {{-- Statistik --}}
            <div style="display:flex;align-items:center;gap:16px;font-size:0.9em;color:#666;margin-bottom:10px;">
                <span><i class="fa fa-eye"></i> {{ $post->views }}</span>

                <a href="{{ route('posts.show', $post) }}" style="color:inherit;text-decoration:none;">
                    <i class="fa fa-comment"></i> {{ $post->comments_count }}
                </a>

                <span><i class="fa fa-thumbs-up"></i> {{ $post->likes }}</span>
                <span><i class="fa fa-thumbs-down"></i> {{ $post->dislikes }}</span>
            </div>

            {{-- Tombol Edit/Hapus hanya pemilik --}}
            @if(Auth::id() === $post->user_id)
            <div style="display:flex;gap:10px;padding-top:10px;border-top:1px solid #eee;">
                <a href="{{ route('posts.edit', $post) }}" style="
                    padding:7px 15px;
                    border-radius:6px;
                    font-size:0.9em;
                    text-decoration:none;
                    background:#40A09C;
                    color:#fff;
                    display:flex;
                    align-items:center;
                    gap:5px;
                ">
                    <i class="fa fa-edit"></i> Edit
                </a>

                <form action="{{ route('posts.destroy', $post) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="
                        padding:7px 15px;
                        border-radius:6px;
                        font-size:0.9em;
                        background:#dc3545;
                        color:#fff;
                        border:none;
                        cursor:pointer;
                        display:flex;
                        align-items:center;
                        gap:5px;
                    " onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                        <i class="fa fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
            @endif

        </div>
        @empty

        <div style="background:#fff;border-radius:7px;box-shadow:0 1px 6px rgba(0,0,0,0.05);padding:20px;text-align:center;">
            <p style="color:#999;">Belum ada postingan pada kategori ini.</p>
        </div>

        @endforelse
    </div>

    {{-- Pagination --}}
    <div style="display:flex;justify-content:center;margin-top:18px;">
        {{ $posts->links() }}
    </div>
</div>

{{-- MODAL LAPORAN --}}
<div id="reportModal"
    style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;
           background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:1000;">

    <div style="background:#fff;padding:20px;border-radius:10px;width:320px;">
        <h3>Laporkan Postingan</h3>

        <form id="reportForm" method="POST">
            @csrf

            <label for="reason">Alasan:</label>
            <select name="reason" id="reason" required
                style="
                    width:100%;
                    box-sizing:border-box;   /* penting biar sejajar */
                    padding:8px;
                    border:1px solid #ddd;
                    border-radius:5px;
                    margin-top:5px;
                ">
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
                    box-sizing:border-box;   /* supaya lebar sama dengan select */
                    border:1px solid #ddd;
                    border-radius:5px;
                    padding:8px;
                    resize:none;
                "
            ></textarea>

            <div style="margin-top:15px;display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeReportModal()"
                    style="background:#ccc;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="background:#f44336;color:#fff;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;">
                    Kirim
                </button>
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
</script>

<style>
.post-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
</style>

@endsection

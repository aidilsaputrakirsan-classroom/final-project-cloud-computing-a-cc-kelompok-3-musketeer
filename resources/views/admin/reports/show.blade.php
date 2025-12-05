<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Detail Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-4" style="
    max-width: 95%;      
    margin-left: auto;
    margin-right: auto;
">

    {{-- TOMBOL KEMBALI --}}
    <a href="{{ url()->previous() }}" 
    style="
            background:#40A09C;
            color:#fff;
            padding:6px 14px;
            border-radius:6px;
            font-size:0.9rem;
            text-decoration:none;
            display:inline-block;
            margin-bottom:15px;
    ">
        Kembali
    </a>

    <!-- CARD POST -->
    <div class="card mb-4">
        <div class="card-body">

            <div class="d-flex align-items-start mb-3">
                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center"
                    style="width:48px;height:48px; background:#40A09C;">
                    {{ strtoupper(substr($report->post->user->name ?? 'U', 0, 1)) }}
                </div>

                <div class="ms-3">
                    <div class="fw-semibold text-dark mb-1">
                        {{ $report->post->user->name ?? '-' }}
                    </div>

                    <small class="text-muted d-block">
                        Dipublikasikan pada {{ optional($report->post->created_at)->format('d F Y, H:i') ?? '-' }}
                        • Diperbarui pada {{ optional($report->post->updated_at)->format('d F Y, H:i') ?? '-' }}
                    </small>
                </div>

                <div class="ms-auto">
                    <button class="btn btn-sm btn-danger rounded-circle">
                        <i class="bi bi-flag-fill"></i>
                    </button>
                </div>
            </div>

            <h4 class="fw-bold mb-2">
                {{ $report->post->title ?? '(Tanpa judul)' }}    
            </h4>

            <hr>

            <p class="mb-3" style="white-space: pre-line; font-size:1.04rem; color:#333;">
                {{ $report->post->body 
                    ?? $report->post->content 
                    ?? '(Tidak ada konten)' }}
            </p>

            @php
                try {
                    // Hitung dari relasi likes/dislikes jika ada
                    $likesCount = method_exists($report->post, 'likes')
                        ? $report->post->likes()->count()
                        : ($report->post->likes ?? 0);

                    $dislikesCount = method_exists($report->post, 'dislikes')
                        ? $report->post->dislikes()->count()
                        : ($report->post->dislikes ?? 0);

                    // PENTING: langsung hitung dari relasi comments(),
                    // jangan pakai comments_count lagi
                    $commentsCount = method_exists($report->post, 'comments')
                        ? $report->post->comments()->count()
                        : ($report->post->comments->count() ?? 0);
                } catch (\Throwable $e) {
                    // Fallback kalau relasi/akses error
                    $likesCount = $report->post->likes ?? 0;
                    $dislikesCount = $report->post->dislikes ?? 0;
                    $commentsCount = $report->post->comments->count() ?? 0;
                }
            @endphp

            <!-- Statistik -->
            <div class="bg-light rounded p-3 mb-3 d-flex flex-wrap gap-4 text-muted">
                <span><i class="bi bi-eye"></i> {{ $report->post->views ?? 0 }} Dilihat</span>
                <span><i class="bi bi-chat"></i> {{ $commentsCount }} Komentar</span>
                <span><i class="bi bi-hand-thumbs-up"></i> {{ $likesCount }} Suka</span>
                <span><i class="bi bi-hand-thumbs-down"></i> {{ $dislikesCount }} Tidak Suka</span>
            </div>

            <span class="badge"
                style="
                    background:#40A09C;
                    color:#fff;
                    padding:5px 14px;
                    border-radius:5px;
                    font-size:0.91em;
                ">
                {{ $report->post->category->name ?? 'Random' }}
            </span>
        </div>
    </div>

    <!-- CARD KOMENTAR -->
    <div class="card mb-4">
        <div class="card-header bg-white fw-semibold">
            Komentar ({{ $commentsCount }})
        </div>
        <div class="card-body">

            @forelse ($report->post->comments as $comment)
                <div class="d-flex mb-3">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                         style="width:38px;height:38px;">
                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                    </div>

                    <div class="ms-3 flex-grow-1">

                        <div class="d-flex align-items-center">
                            <span class="fw-semibold me-2">{{ $comment->user->name ?? '-' }}</span>
                            <small class="text-muted">{{ $comment->created_at->format('d M Y, H:i') }}</small>
                        </div>

                        <!-- isi komentar -->
                        <div class="mt-1 text-dark">
                            {{ $comment->body }}
                        </div>

                        {{-- Balasan --}}
                        @if($comment->replies && $comment->replies->count())
                            <div class="border-start ps-3 mt-2">
                                @foreach($comment->replies as $reply)
                                    <div class="d-flex mb-2">
                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center"
                                             style="width:28px;height:28px;">
                                            {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="ms-2">
                                            <div class="d-flex align-items-center">
                                                <span class="fw-semibold me-2">{{ $reply->user->name }}</span>
                                                <small class="text-muted">{{ $reply->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                            <div class="mt-1 text-dark">
                                                {{ $reply->body }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>

                <hr>
            @empty
                <p class="text-muted mb-0">Belum ada komentar pada postingan ini.</p>
            @endforelse

        </div>
    </div>

    <!-- CARD LAPORAN -->
    <div class="card mb-4">
        <div class="card-header bg-white fw-semibold">
            Laporan Pengguna
        </div>
        <div class="card-body">

            <p class="text-muted">
                Total <strong>{{ $allReportsForPost->count() }}</strong> laporan untuk postingan ini.
            </p>

            <div class="table-responsive mb-4">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Pelapor</th>
                            <th>Alasan</th>
                            <th>Detail</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allReportsForPost as $r)
                            <tr>
                                <td>{{ $r->user->name ?? '-' }}</td>
                                <td>{{ $r->reason }}</td>
                                <td style="max-width:360px;white-space:pre-line;">
                                    {{ $r->details }}
                                </td>
                                <td>{{ $r->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada laporan lain untuk postingan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2">
                {{-- TOLAK LAPORAN – pakai route status --}}
                <form method="POST"
                      action="{{ route('admin.reports.status', $report) }}"
                      onsubmit="return confirm('Tolak semua laporan untuk postingan ini?')">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button class="btn btn-danger">Tolak Laporan</button>
                </form>

                {{-- TERIMA LAPORAN – pakai route status --}}
                <form method="POST"
                      action="{{ route('admin.reports.status', $report) }}"
                      onsubmit="return confirm('Terima semua laporan untuk postingan ini?')">
                    @csrf
                    <input type="hidden" name="status" value="accepted">
                    <button class="btn btn-success">Terima Laporan</button>
                </form>
            </div>

        </div>
    </div>

</div>

</body>
</html>

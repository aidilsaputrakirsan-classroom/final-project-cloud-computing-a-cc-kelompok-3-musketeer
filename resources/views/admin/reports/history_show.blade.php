<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Detail Laporan (History)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-4" style="max-width:95%;margin-left:auto;margin-right:auto;">

    {{-- TOMBOL KEMBALI --}}
    <a href="{{ route('admin.reports.history') }}"
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

    {{-- ========================
         POST MASIH ADA?
       ======================== --}}
    @if($report->post)

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
                        // Hitung dari relasi reactions
                        $likesCount = method_exists($report->post, 'likes')
                            ? $report->post->likes()->count()
                            : ($report->post->likes ?? 0);

                        $dislikesCount = method_exists($report->post, 'dislikes')
                            ? $report->post->dislikes()->count()
                            : ($report->post->dislikes ?? 0);

                        // PENTING: langsung hitung dari relasi comments(),
                        // JANGAN pakai comments_count lagi
                        $commentsCount = method_exists($report->post, 'comments')
                            ? $report->post->comments()->count()
                            : ($report->post->comments->count() ?? 0);
                    } catch (\Throwable $e) {
                        // Fallback jika ada error di relasi
                        $likesCount = $report->post->likes ?? 0;
                        $dislikesCount = $report->post->dislikes ?? 0;
                        $commentsCount = $report->post->comments->count() ?? 0;
                    }
                @endphp

                <!-- Statistik -->
                <div class="bg-light rounded p-3 mb-3 d-flex flex-wrap gap-4 text-muted">
                    <span>{{ $report->post->views ?? 0 }} Dilihat</span>
                    <span>{{ $commentsCount }} Komentar</span>
                    <span>{{ $likesCount }} Suka</span>
                    <span>{{ $dislikesCount }} Tidak Suka</span>
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

        {{-- CARD KOMENTAR --}}
        <div class="card mb-4">
            <div class="card-header bg-white fw-semibold">
                {{-- pakai nilai yang sudah dihitung --}}
                Komentar ({{ $commentsCount }})
            </div>
            <div class="card-body">

                @forelse ($report->post->comments as $comment)
                    <div class="d-flex mb-3">
                        <div style="
                            width:38px;height:38px;border-radius:50%;
                            background:#40A09C;color:#fff;
                            display:flex;align-items:center;justify-content:center;
                            font-weight:600;
                        ">
                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                        </div>

                        <div class="ms-3 flex-grow-1">
                            <div class="d-flex align-items-center">
                                <span class="fw-semibold me-2">{{ $comment->user->name ?? '-' }}</span>
                                <small class="text-muted">{{ $comment->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            <div class="mt-1 text-dark">
                                {{ $comment->body }}
                            </div>
                        </div>
                    </div>
                    <hr>
                @empty
                    <p class="text-muted mb-0">Belum ada komentar.</p>
                @endforelse

            </div>
        </div>

    @else
        {{-- ============================
             POST SUDAH DIHAPUS
           ============================ --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-2" style="color:#d42b2b">Postingan Telah Dihapus</h5>
                <p class="text-muted mb-0">
                    Postingan yang dilaporkan telah dihapus oleh admin saat laporan diterima.
                    Namun data laporan tetap disimpan untuk arsip.
                </p>
            </div>
        </div>
    @endif


    {{-- ========== CARD RINGKASAN & DAFTAR LAPORAN ========== --}}
    <div class="card mb-4">
        <div class="card-header bg-white fw-semibold">
            Ringkasan Keputusan Laporan
        </div>
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Status Laporan</label><br>
                @if($report->status === 'accepted')
                    <span class="badge bg-success">Diterima</span>
                @elseif($report->status === 'rejected')
                    <span class="badge bg-danger">Ditolak</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($report->status) }}</span>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Diproses oleh</label>
                <input type="text" class="form-control" readonly
                       value="{{ $report->handledBy->name ?? '-' }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Diproses</label>
                <input type="text" class="form-control" readonly
                       value="{{ optional($report->updated_at)->format('d F Y, H:i') ?? '-' }}">
            </div>

            <hr class="my-3">

            <h6 class="fw-semibold mb-2">Daftar Laporan dari Pengguna</h6>
            <p class="text-muted" style="font-size:0.9rem;">
                Total <strong>{{ $allReportsForPost->count() }}</strong> laporan untuk postingan ini.
            </p>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Pelapor</th>
                            <th>Alasan</th>
                            <th>Detail Laporan</th>
                            <th>Dibuat Pada</th>
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
                                    Tidak ada data laporan lain untuk postingan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>

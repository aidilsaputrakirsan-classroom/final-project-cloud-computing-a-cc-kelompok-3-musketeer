@extends('layouts.admin')

@section('title', 'Laporan Postingan - Admin')

@section('content')
<div class="admin-reports-page" style="padding:28px;">

    <h1 style="font-size:40px;margin:0 0 18px">Laporan Postingan</h1>
    <p style="color:#6b7280;margin-top:6px;margin-bottom:18px">
        Menampilkan <strong>laporan masuk (Pending)</strong> yang membutuhkan aksi admin.
    </p>

    <!-- Statistik cards (Accepted/Rejected clickable to history) -->
    <div class="cards-row" style="display:flex;gap:18px;margin-bottom:22px;flex-wrap:wrap;">
        <div class="card stat-card"
             style="background:#fff;padding:18px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06);min-width:220px;display:flex;align-items:center;gap:12px;">
            <div class="icon"
                 style="width:56px;height:56px;border-radius:10px;background:#e6f4f8;display:flex;align-items:center;justify-content:center;font-weight:700;color:#40A09C">!</div>
            <div>
                <div style="font-size:20px;font-weight:700">{{ $counts['pending'] }}</div>
                <div style="color:#9aa0a6">Total Laporan</div>
            </div>
        </div>

        <a href="{{ route('admin.reports.history', ['status' => 'accepted']) }}" style="text-decoration:none;">
            <div class="card stat-card"
                 style="background:#fff;padding:18px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06);min-width:220px;display:flex;align-items:center;gap:12px;">
                <div style="width:56px;height:56px;border-radius:50%;background:#e9fbef;display:flex;align-items:center;justify-content:center;color:#40A09C;font-weight:700">✓</div>
                <div>
                    <div style="font-size:20px;font-weight:700">{{ $counts['accepted'] }}</div>
                    <div style="color:#9aa0a6">Laporan Diterima</div>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.history', ['status' => 'rejected']) }}" style="text-decoration:none;">
            <div class="card stat-card"
                 style="background:#fff;padding:18px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06);min-width:220px;display:flex;align-items:center;gap:12px;">
                <div style="width:56px;height:56px;border-radius:50%;background:#fff1f1;display:flex;align-items:center;justify-content:center;color:#d42b2b;font-weight:700">✕</div>
                <div>
                    <div style="font-size:20px;font-weight:700">{{ $counts['rejected'] }}</div>
                    <div style="color:#9aa0a6">Laporan Ditolak</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Table panel (responsive wrapper) -->
    <div class="panel" style="background:#fff;padding:22px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06)">
        <div class="table-card" style="border:4px solid #40A09C;border-radius:6px;padding:12px;">

            <!-- responsive container: allows horizontal scroll on small screens -->
            <div class="table-responsive" style="overflow-x:auto;">
                <table class="reports-table" style="width:100%;min-width:900px;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#40A09C;color:#ffffff;">
                            <th style="padding:12px;border:2px solid #40A09C;text-align:left;white-space:nowrap;color:#fff">Judul Postingan</th>
                            <th style="padding:12px;border:2px solid #40A09C;text-align:left;white-space:nowrap;color:#fff">Alasan Pelaporan</th>
                            <th style="padding:12px;border:2px solid #40A09C;text-align:left;white-space:nowrap;color:#fff">Detail Pelaporan</th>
                            <th style="padding:12px;border:2px solid #40A09C;text-align:center;white-space:nowrap;width:220px;color:#fff">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groupedReports as $postId => $reportsForPost)
                            @php
                                /** @var \App\Models\Report $firstReport */
                                $firstReport   = $reportsForPost->first();
                                $post          = $firstReport->post;
                                $jumlahLaporan = $reportsForPost->count();
                                $lastReport    = $reportsForPost->sortByDesc('created_at')->first();
                            @endphp
                            <tr>
                                {{-- Judul Postingan --}}
                                <td style="padding:12px;border:2px solid #e6f4f1;vertical-align:top">
                                    <div style="font-weight:600;color:#0f3936">
                                        {{ $post->title ?? '(post tidak ditemukan)' }}
                                    </div>
                                    <div style="font-size:12px;color:#666;margin-top:6px">
                                        Total laporan: <strong>{{ $jumlahLaporan }}</strong><br>
                                        Laporan terakhir: {{ $lastReport->created_at->format('Y-m-d H:i') }}
                                    </div>
                                </td>

                                {{-- Ringkasan Alasan dari beberapa pelapor --}}
                                <td style="padding:12px;border:2px solid #e6f4f1;vertical-align:top;max-width:260px;word-wrap:break-word;color:#333;">
                                    <div style="font-size:13px;margin-bottom:6px;">
                                        <strong>{{ $jumlahLaporan }}</strong> laporan dari pengguna.
                                    </div>

                                    @foreach($reportsForPost->take(2) as $r)
                                        <div style="font-size:12px;color:#444;margin-bottom:4px;">
                                            • {{ $r->reason }}
                                            <span style="color:#777;">— dari {{ $r->user->name ?? 'User' }}</span>
                                        </div>
                                    @endforeach

                                    @if($jumlahLaporan > 2)
                                        <div style="font-size:12px;color:#999;">
                                            + {{ $jumlahLaporan - 2 }} laporan lainnya
                                        </div>
                                    @endif
                                </td>

                                {{-- Detail Pelaporan per pengguna --}}
                                <td style="padding:12px;border:2px solid #e6f4f1;vertical-align:top;max-width:380px;word-wrap:break-word;color:#333;">
                                    @foreach($reportsForPost->take(2) as $r)
                                        <div style="font-size:12px;color:#444;margin-bottom:6px;">
                                            • {{ \Illuminate\Support\Str::limit($r->details, 140) }}
                                            <span style="color:#777;display:block;margin-top:2px;">
                                                — dari {{ $r->user->name ?? 'User' }}
                                            </span>
                                        </div>
                                    @endforeach

                                    @if($jumlahLaporan > 2)
                                        <div style="font-size:12px;color:#999;">
                                            + {{ $jumlahLaporan - 2 }} detail laporan lainnya
                                        </div>
                                    @endif
                                </td>

                                {{-- Aksi: hanya tombol Detail --}}
                                <td style="padding:12px;border:2px solid #e6f4f1;text-align:center;vertical-align:top">
                                    <div class="action-row" style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                                        <!-- Detail semua laporan untuk postingan ini -->
                                        <a href="{{ route('admin.reports.show', $firstReport) }}" title="Detail" class="btn-detail"
                                           style="display:inline-block;padding:8px 10px;border-radius:6px;background:#40A09C;color:#fff;text-decoration:none">
                                            Detail
                                        </a>
                                    </div>

                                    <!-- pending badge -->
                                    <div style="margin-top:10px;font-size:13px;color:#333">
                                        <span style="display:inline-block;padding:6px 10px;background:#e8f7f5;color:#066557;border-radius:6px">
                                            {{ $jumlahLaporan }} laporan pending
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding:18px;text-align:center;color:#777">
                                    Belum ada laporan pending.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> {{-- .table-responsive --}}

        </div>

        <div style="margin-top:14px;">
            {{ $reports->links() }}
        </div>
    </div>
</div>

{{-- RESPONSIVE CSS --}} 
<style>
@media (max-width: 880px) {
    .cards-row { justify-content:flex-start; }
    .stat-card { min-width: calc(50% - 12px); }
}
@media (max-width: 520px) {
    .stat-card { min-width: 100%; }
}
.reports-table button,
.reports-table a.btn-detail {
    min-width:44px;
}
.reports-table td { word-break: break-word; }
.table-responsive {
    -webkit-overflow-scrolling: touch;
}
</style>

@endsection

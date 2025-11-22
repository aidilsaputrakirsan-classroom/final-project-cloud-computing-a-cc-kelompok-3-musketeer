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
                <div style="font-size:20px;font-weight:700">{{ $counts['total'] }}</div>
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
                        @forelse($reports as $r)
                        <tr>
                            <td style="padding:12px;border:2px solid #e6f4f1;vertical-align:top">
                                <div style="font-weight:600;color:#0f3936">{{ $r->post->title ?? '(post tidak ditemukan)' }}</div>
                                <div style="font-size:12px;color:#666;margin-top:6px">
                                    Pelapor: {{ $r->user->name ?? '-' }} — {{ $r->created_at->format('Y-m-d H:i') }}
                                </div>
                            </td>

                            <td style="padding:12px;border:2px solid #e6f4f1;vertical-align:top;max-width:240px;word-wrap:break-word;color:#333;">
                                {{ $r->reason }}
                            </td>

                            <td style="padding:12px;border:2px solid #e6f4f1;vertical-align:top;max-width:380px;word-wrap:break-word;color:#333;">
                                {{ \Illuminate\Support\Str::limit($r->details, 300) }}
                            </td>

                            <td style="padding:12px;border:2px solid #e6f4f1;text-align:center;vertical-align:top">
                                <div class="action-row" style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                                    <!-- Terima -->
                                    <form method="POST"
                                          action="{{ route('admin.reports.status', $r) }}"
                                          onsubmit="return confirm('Terima laporan ini?')"
                                          style="display:inline-block;">
                                        @csrf
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" title="Terima" class="btn-accept"
                                                style="border:none;padding:8px 10px;border-radius:6px;background:#28a745;color:#fff;cursor:pointer">
                                            ✔
                                        </button>
                                    </form>

                                    <!-- Tolak -->
                                    <form method="POST"
                                          action="{{ route('admin.reports.status', $r) }}"
                                          onsubmit="return confirm('Tolak laporan ini?')"
                                          style="display:inline-block;">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" title="Tolak" class="btn-reject"
                                                style="border:none;padding:8px 10px;border-radius:6px;background:#e74c3c;color:#fff;cursor:pointer">
                                            ✕
                                        </button>
                                    </form>

                                    <!-- Detail -->
                                    <a href="{{ route('admin.reports.show', $r) }}" title="Detail" class="btn-detail"
                                       style="display:inline-block;padding:8px 10px;border-radius:6px;background:#40A09C;color:#fff;text-decoration:none">
                                        Detail
                                    </a>
                                </div>

                                <!-- pending badge only (index = pending) -->
                                <div style="margin-top:10px;font-size:13px;color:#333">
                                    <span style="display:inline-block;padding:6px 10px;background:#e8f7f5;color:#066557;border-radius:6px">
                                        Pending
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

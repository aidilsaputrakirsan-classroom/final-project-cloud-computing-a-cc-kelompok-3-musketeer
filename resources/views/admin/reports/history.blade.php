@extends('layouts.admin')

@section('title', 'History Laporan - Admin')

@section('content')
<div class="admin-reports-page" style="padding:28px;">

    <h1 style="font-size:40px;margin:0 0 18px">History Laporan</h1>
    <p style="color:#666;margin-bottom:18px">
        Berikut riwayat laporan yang telah <strong>diterima</strong> atau <strong>ditolak</strong>.
    </p>

    <!-- tombol kembali ke pending -->
    <div style="margin-bottom:14px;">
        <a href="{{ route('admin.reports.index') }}"
           style="display:inline-block;padding:8px 12px;background:#40A09C;border-radius:6px;text-decoration:none;color:#ffffff">
            Lihat Laporan Pending
        </a>
    </div>

    <div class="panel" style="background:#fff;padding:22px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06)">

        {{-- FILTER STATUS HISTORY --}}
        <div style="margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <form method="GET" action="{{ route('admin.reports.history') }}">
                <label for="status" style="font-size:14px;margin-right:6px;">Filter Status:</label>
                <select name="status" id="status"
                        onchange="this.form.submit()"
                        style="padding:6px 10px;border-radius:6px;border:1px solid #ccc;min-width:150px;">
                    <option value="">Semua</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>

            @isset($counts)
                <div style="font-size:13px;color:#444;">
                    <strong>Total:</strong> {{ $counts['total'] ?? 0 }} |
                    <span style="color:#0c665d;">Diterima: {{ $counts['accepted'] ?? 0 }}</span> |
                    <span style="color:#b02020;">Ditolak: {{ $counts['rejected'] ?? 0 }}</span> |
                    <span>Pending: {{ $counts['pending'] ?? 0 }}</span>
                </div>
            @endisset
        </div>
        {{-- END FILTER STATUS HISTORY --}}

        <div class="table-card" style="border:4px solid #40A09C;border-radius:6px;padding:12px;">

            <div class="table-responsive" style="overflow-x:auto;">
                <table class="reports-table" style="width:100%;min-width:900px;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#40A09C;color:white;">
                            <th style="padding:12px;border:2px solid #40A09C;text-align:left;color:white">Judul Postingan</th>
                            <th style="padding:12px;border:2px solid #40A09C;text-align:left;color:white">Alasan Pelaporan</th>
                            <th style="padding:12px;border:2px solid #40A09C;text-align:left;color:white">Detail Pelaporan</th>
                            <th style="padding:12px;border:2px solid #40A09C;text-align:center;width:210px;color:white">Status / Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // kelompokkan laporan history per post_id (accepted + rejected)
                            $groupedReports = $reports->groupBy('post_id');
                        @endphp

                        @forelse($groupedReports as $postId => $reportsForPost)
                            @php
                                /** @var \App\Models\Report $firstReport */
                                $firstReport   = $reportsForPost->first();
                                $post          = $firstReport->post;
                                $jumlahLaporan = $reportsForPost->count();
                                $lastReport    = $reportsForPost->sortByDesc('updated_at')->first();
                                $status        = $firstReport->status; // diasumsikan sama utk semua
                            @endphp

                            <tr>
                                {{-- Judul Postingan --}}
                                <td style="padding:12px;border:2px solid #e0f1ee;vertical-align:top">
                                    <div style="font-weight:600;color:#0a3836">
                                        {{ $post->title ?? '(post tidak ditemukan)' }}
                                    </div>
                                    <div style="font-size:12px;color:#666;margin-top:6px">
                                        Total laporan: <strong>{{ $jumlahLaporan }}</strong><br>
                                        Terakhir diproses: {{ $lastReport->updated_at->format('Y-m-d H:i') }}
                                    </div>
                                </td>

                                {{-- Alasan Pelaporan per pengguna --}}
                                <td style="padding:12px;border:2px solid #e0f1ee;vertical-align:top;max-width:260px;word-wrap:break-word;">
                                    <div style="font-size:13px;margin-bottom:6px;color:#333;">
                                        <strong>{{ $jumlahLaporan }}</strong> laporan dari pengguna.
                                    </div>

                                    @foreach($reportsForPost->take(3) as $r)
                                        <div style="font-size:12px;color:#444;margin-bottom:4px;">
                                            • {{ $r->reason }}
                                            <span style="color:#777;">— {{ $r->user->name ?? 'User' }}</span>
                                        </div>
                                    @endforeach

                                    @if($jumlahLaporan > 3)
                                        <div style="font-size:12px;color:#999;">
                                            + {{ $jumlahLaporan - 3 }} alasan laporan lainnya
                                        </div>
                                    @endif
                                </td>

                                {{-- Detail Pelaporan per pengguna --}}
                                <td style="padding:12px;border:2px solid #e0f1ee;vertical-align:top;max-width:380px;word-wrap:break-word;">
                                    @foreach($reportsForPost->take(3) as $r)
                                        <div style="font-size:12px;color:#444;margin-bottom:6px;">
                                            • {{ \Illuminate\Support\Str::limit($r->details, 160) }}
                                            <span style="color:#777;display:block;margin-top:2px;">
                                                — {{ $r->user->name ?? 'User' }}
                                            </span>
                                        </div>
                                    @endforeach

                                    @if($jumlahLaporan > 3)
                                        <div style="font-size:12px;color:#999;">
                                            + {{ $jumlahLaporan - 3 }} detail laporan lainnya
                                        </div>
                                    @endif
                                </td>

                                {{-- Status / Aksi --}}
                                <td style="padding:12px;border:2px solid #e0f1ee;text-align:center;vertical-align:top">
                                    {{-- status --}}
                                    <div style="margin-bottom:8px">
                                        @if($status === 'accepted')
                                            <span style="display:inline-block;padding:6px 10px;background:#e2f7f3;color:#0c665d;border-radius:6px">
                                                Diterima
                                            </span>
                                        @else
                                            <span style="display:inline-block;padding:6px 10px;background:#fdeaea;color:#b02020;border-radius:6px">
                                                Ditolak
                                            </span>
                                        @endif
                                    </div>

                                    <div style="font-size:12px;color:#666;margin-bottom:10px;">
                                        oleh <strong>{{ $firstReport->handledBy->name ?? '-' }}</strong><br>
                                        <small>{{ $firstReport->updated_at->format('Y-m-d H:i') }}</small>
                                    </div>

                                    <div style="margin-top:4px;">
                                        <a href="{{ route('admin.reports.history.show', $firstReport) }}"
                                           style="display:inline-block;padding:7px 10px;border-radius:6px;background:#40A09C;color:#fff;text-decoration:none">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding:18px;text-align:center;color:#777">
                                    Belum ada laporan history.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <div style="margin-top:14px;">
            {{ $reports->links() }}
        </div>
    </div>
</div>

{{-- KECILKAN ICON PANAH PAGINATION (TAILWIND DEFAULT) --}}
<style>
    /* nav pagination bawaan laravel (role="navigation") */
    nav[role="navigation"] svg {
        width: 18px !important;
        height: 18px !important;
    }

    /* opsional: rapikan teks halaman */
    nav[role="navigation"] span,
    nav[role="navigation"] a {
        font-size: 0.9rem;
    }
</style>

@endsection

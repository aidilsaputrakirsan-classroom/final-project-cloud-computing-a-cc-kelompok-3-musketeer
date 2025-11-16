@extends('layouts.admin')

@section('title', 'History Laporan - Admin')

@section('content')
<div class="admin-reports-page" style="padding:28px;">

    <h1 style="font-size:40px;margin:0 0 18px">History Laporan</h1>
    <p style="color:#666;margin-bottom:18px">Berikut riwayat laporan yang telah <strong>diterima</strong> atau <strong>ditolak</strong>.</p>

    <!-- tombol kembali ke pending -->
    <div style="margin-bottom:14px;">
        <a href="{{ route('admin.reports.index') }}"
           style="display:inline-block;padding:8px 12px;background:#40A09C;border-radius:6px;text-decoration:none;color:#ffffff">
            Lihat Laporan Pending
        </a>
    </div>

    <div class="panel" style="background:#fff;padding:22px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06)">
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
                        @forelse($reports as $r)
                        <tr>
                            <td style="padding:12px;border:2px solid #e0f1ee;vertical-align:top">
                                <div style="font-weight:600;color:#0a3836">{{ $r->post->title ?? '(post tidak ditemukan)' }}</div>
                                <div style="font-size:12px;color:#666;margin-top:6px">
                                    Pelapor: {{ $r->user->name ?? '-' }} — {{ $r->created_at->format('Y-m-d H:i') }}
                                </div>
                            </td>

                            <td style="padding:12px;border:2px solid #e0f1ee;vertical-align:top;max-width:240px;word-wrap:break-word;">
                                {{ $r->reason }}
                            </td>

                            <td style="padding:12px;border:2px solid #e0f1ee;vertical-align:top;max-width:380px;word-wrap:break-word;">
                                {{ \Illuminate\Support\Str::limit($r->details, 400) }}
                            </td>

                            <td style="padding:12px;border:2px solid #e0f1ee;text-align:center;vertical-align:top">

                                {{-- status --}}
                                <div style="margin-bottom:8px">
                                    @if($r->status === 'accepted')
                                        <span style="display:inline-block;padding:6px 10px;background:#e2f7f3;color:#0c665d;border-radius:6px">
                                            Diterima
                                        </span>
                                    @else
                                        <span style="display:inline-block;padding:6px 10px;background:#fdeaea;color:#b02020;border-radius:6px">
                                            Ditolak
                                        </span>
                                    @endif
                                </div>

                                <div style="font-size:12px;color:#666;">
                                    oleh <strong>{{ $r->handledBy->name ?? '-' }}</strong><br>
                                    <small>{{ $r->updated_at->format('Y-m-d H:i') }}</small>
                                </div>

                                <div style="margin-top:10px;">
                                    <a href="{{ route('admin.reports.show', $r) }}"
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
@endsection

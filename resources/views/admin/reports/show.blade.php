
<div class="container" style="padding:28px;">

    <!-- Back -->
    <div style="margin-bottom:18px;">
        <a href="{{ url()->previous() }}" style="text-decoration:none;color:#4b3b39;display:inline-flex;align-items:center;gap:10px">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="vertical-align:middle">
                <path d="M15 18l-6-6 6-6" stroke="#6b4f45" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span style="font-weight:600">Kembali</span>
        </a>
    </div>

    <h2 style="margin:0 0 10px;font-size:24px;color:#3b3b3b">Detail Laporan</h2>

    <!-- Post card -->
    <div style="background:#fff;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06);padding:18px;margin-top:14px;">
        <div style="display:flex;gap:12px;align-items:flex-start;">
            <!-- avatar -->
            <div style="width:56px;height:56px;border-radius:50%;background:#f3f6f7;flex:0 0 56px;overflow:hidden;display:flex;align-items:center;justify-content:center;font-weight:700;color:#40A09C">
                {{ strtoupper(substr($report->post->user->name ?? 'U',0,1)) }}
            </div>

            <div style="flex:1">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <div style="font-weight:700;font-size:16px;color:#222">
                            {{ $report->post->user->name ?? 'Unknown' }}
                        </div>
                        <div style="font-size:12px;color:#888;margin-top:4px;">
                            {{ optional($report->post->created_at)->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>
                </div>

                <div style="margin-top:14px;color:#2f2f2f;line-height:1.6;">
                    {!! nl2br(e($report->post->body ?? $report->post->content ?? '')) !!}
                </div>

                <div style="display:flex;gap:18px;align-items:center;margin-top:16px;color:#6b7280;font-size:13px">
                    <div style="display:flex;align-items:center;gap:8px">
                        <i class="fa fa-eye" aria-hidden="true"></i> <span>{{ $report->post->views ?? 0 }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <i class="fa fa-comment" aria-hidden="true"></i> <span>{{ $report->post->comments ? $report->post->comments->count() : 0 }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <i class="fa fa-thumbs-up" aria-hidden="true"></i> <span>{{ $report->post->likes ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments (if any) -->
    <div style="background:#fff;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.03);padding:18px;margin-top:18px;">
        <h3 style="margin:0 0 12px;font-size:18px;color:#333">Komentar</h3>

        @if($report->post && $report->post->comments && $report->post->comments->count())
            <div style="display:flex;flex-direction:column;gap:12px">
                @foreach($report->post->comments as $comment)
                    <div style="display:flex;gap:12px;align-items:flex-start;border-top:1px solid #f1f1f1;padding-top:12px">
                        <div style="width:40px;height:40px;border-radius:50%;background:#f3f6f7;display:flex;align-items:center;justify-content:center;font-weight:700;color:#40A09C;flex:0 0 40px">
                            {{ strtoupper(substr($comment->user->name ?? 'U',0,1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700;color:#222">{{ $comment->user->name ?? 'Anonymous' }} <small style="color:#999;font-weight:400;margin-left:8px">{{ optional($comment->created_at)->format('H:i \p\m') }}</small></div>
                            <div style="color:#444;margin-top:6px">{{ $comment->body }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="color:#777;padding:12px 0">Belum ada komentar.</div>
        @endif
    </div>

    <!-- Laporan panel -->
    <div style="background:#fff;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06);padding:18px;margin-top:18px;">
        <h3 style="margin:0 0 12px;color:#3b3b3b">Laporan Pengguna</h3>

        <form method="POST" action="{{ route('admin.reports.status', $report) }}">
            @csrf

            <div style="margin-bottom:12px">
                <label style="display:block;font-weight:600;margin-bottom:6px;color:#555">Alasan</label>
                <input type="text" readonly
                       value="{{ $report->reason }}"
                       style="width:100%;padding:11px;border:1px solid #ebeaea;border-radius:6px;background:#fafafa;color:#333" />
            </div>

            <div style="margin-bottom:14px">
                <label style="display:block;font-weight:600;margin-bottom:6px;color:#555">Detail</label>
                <textarea readonly rows="3" style="width:100%;padding:11px;border:1px solid #ebeaea;border-radius:6px;background:#fafafa;color:#333">{{ $report->details }}</textarea>
            </div>

            <div style="display:flex;gap:12px;align-items:center">
                <button type="submit" name="status" value="accepted" onclick="return confirm('Terima laporan ini?')"
                        style="background:#28a745;border:none;color:#fff;padding:10px 18px;border-radius:6px;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,0.04)">
                    <i class="fa fa-check" style="margin-right:8px"></i> Terima Laporan
                </button>

                <button type="submit" name="status" value="rejected" onclick="return confirm('Tolak laporan ini?')"
                        style="background:#e74c3c;border:none;color:#fff;padding:10px 18px;border-radius:6px;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,0.04)">
                    <i class="fa fa-times" style="margin-right:8px"></i> Tolak Laporan
                </button>

                <a href="{{ route('admin.reports.index') }}" style="margin-left:auto;text-decoration:none;color:#666;padding:8px 12px;border-radius:6px;border:1px solid #eee;background:#fafafa">Kembali ke Daftar</a>
            </div>
        </form>
    </div>

</div>

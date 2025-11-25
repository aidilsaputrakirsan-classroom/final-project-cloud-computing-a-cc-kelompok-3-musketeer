@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
    <style>
        .table-head {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #6c7a89;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .table-cell {
            padding: 14px 16px;
            font-size: 0.96rem;
            color: #2f3b52;
            vertical-align: top;
        }
        .filter-label {
            display: block;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #718096;
            margin-bottom: 6px;
            letter-spacing: 0.05em;
        }
    </style>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <h1 style="margin:0;font-size:1.6rem;color:#2f3b52;">Activity Logs</h1>
            <p style="margin:4px 0 0;color:#6c7a89;">Pantau aktivitas penting pengguna dan admin.</p>
        </div>
    </div>

    <form method="GET" style="background:#fff;padding:16px;border-radius:10px;box-shadow:0 4px 10px rgba(15,23,42,0.08);margin-bottom:20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
        <div>
            <label class="filter-label">User</label>
            <select name="user" style="width:100%;padding:8px;border-radius:6px;border:1px solid #d7dde4;">
                <option value="">Semua</option>
                @foreach($users as $id => $name)
                    <option value="{{ $id }}" @selected(request('user') == $id)>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="filter-label">Action (prefix)</label>
            <input type="text" name="action" value="{{ request('action') }}" placeholder="mis. post."
                   style="width:100%;padding:8px;border-radius:6px;border:1px solid #d7dde4;">
        </div>
        <div>
            <label class="filter-label">Cari deskripsi</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="keyword"
                   style="width:100%;padding:8px;border-radius:6px;border:1px solid #d7dde4;">
        </div>
        <div>
            <label class="filter-label">Dari tanggal</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   style="width:100%;padding:8px;border-radius:6px;border:1px solid #d7dde4;">
        </div>
        <div>
            <label class="filter-label">Sampai tanggal</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   style="width:100%;padding:8px;border-radius:6px;border:1px solid #d7dde4;">
        </div>
        <div style="display:flex;align-items:flex-end;gap:8px;">
            <button type="submit" style="padding:10px 16px;background:#40A09C;color:#fff;border:none;border-radius:6px;cursor:pointer;">Filter</button>
            <a href="{{ route('admin.activity-logs.index') }}" style="padding:10px 16px;border:1px solid #d7dde4;border-radius:6px;color:#3b454b;text-decoration:none;">Reset</a>
        </div>
    </form>

    <div style="background:#fff;border-radius:12px;box-shadow:0 10px 25px rgba(15,23,42,0.12);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f7fafc;">
            <tr>
                <th class="table-head">User</th>
                <th class="table-head">Action</th>
                <th class="table-head">Description</th>
                <th class="table-head">Context</th>
                <th class="table-head">Timestamp</th>
                <th class="table-head">Detail</th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr style="border-bottom:1px solid #eef2f7;">
                    <td class="table-cell">{{ $log->user->name ?? 'System' }}</td>
                    <td class="table-cell">{{ $log->action }}</td>
                    <td class="table-cell">{{ $log->description }}</td>
                    <td class="table-cell">{{ $log->context ?? '—' }}</td>
                    <td class="table-cell">
                        {{ $log->created_at?->timezone(config('app.timezone'))->format('d M Y H:i') }}
                        <div style="font-size:0.8em;color:#8e9baa;">IP: {{ $log->ip_address ?? 'n/a' }}</div>
                    </td>
                    <td class="table-cell">
                        @if($log->detail)
                            <details>
                                <summary style="cursor:pointer;color:#40A09C;">Lihat</summary>
                                <pre style="background:#f8fafc;padding:10px;border-radius:8px;white-space:pre-wrap;font-size:0.85em;">{{ json_encode($log->detail, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </details>
                        @else
                            <span style="color:#a0aec0;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:24px;color:#7b8a9d;">Belum ada aktivitas yang tercatat.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="padding:14px 18px;background:#f7fafc;display:flex;justify-content:flex-end;">
            {{ $logs->links() }}
        </div>
    </div>
@endsection


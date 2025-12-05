@extends('layouts.main')

@section('title', 'Daftar Suka | Chatter Box')

@section('content')
<div class="container" style="padding: 20px; max-width: 980px; margin: 0 auto;">

    <h2 style="margin-bottom: 20px; font-weight: 600; color:#314057;">
        Daftar Suka — Postingan Saya
    </h2>

    @if($posts->isEmpty())
        <div style="
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            color: #666;">
            Anda belum memiliki postingan atau belum ada yang memberikan reaksi apa pun.
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:16px;">

            @foreach($posts as $post)
                @php
                    // karena di controller sudah with(['reactions.user'])
                    $likes = $post->reactions->where('reaction', 1);
                    $dislikes = $post->reactions->where('reaction', -1);
                @endphp

                <div style="
                    background: #fff;
                    padding: 18px;
                    border-radius: 8px;
                    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
                ">

                    {{-- Info Post --}}
                    <div style="display:flex;justify-content:space-between;gap:12px;align-items:flex-start;">
                        <div style="flex:1;min-width:0;">
                            <h4 style="margin:0 0 6px 0;font-size:1.05em;line-height:1.2;">
                                <a href="{{ route('posts.show', $post) }}"
                                   style="text-decoration:none; color:#2b3d4f;">
                                    {{ $post->title }}
                                </a>
                            </h4>

                            <p style="color:#555;margin:0 0 8px;font-size:0.95em;">
                                {{ \Illuminate\Support\Str::limit($post->content, 140) }}
                            </p>
                        </div>

                        {{-- Statistik Like & Dislike + tombol detail --}}
                        <div style="display:flex;align-items:center;gap:16px;margin-left:12px;white-space:nowrap;">
                            <div style="text-align:center;">
                                <div style="font-size:1em;color:#0d6efd;font-weight:700;">
                                    {{ $likes->count() }}
                                </div>
                                <div style="font-size:0.85em;color:#666;">Suka</div>
                            </div>

                            <div style="text-align:center;">
                                <div style="font-size:1em;color:#dc3545;font-weight:700;">
                                    {{ $dislikes->count() }}
                                </div>
                                <div style="font-size:0.85em;color:#666;">Tidak Suka</div>
                            </div>

                            <div>
                                <a href="{{ route('posts.reactions', $post) }}"
                                   style="padding: 8px 14px; background:#40A09C; color:#fff;
                                          border-radius:6px; text-decoration:none; font-weight:600;">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel siapa saja yang bereaksi pada postingan ini --}}
                    <div style="margin-top:12px;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.9em;">
                            <thead>
                                <tr style="background:#f8f9fa;">
                                    <th style="padding:8px;border-bottom:1px solid #e5e5e5;text-align:left;">
                                        Pengguna
                                    </th>
                                    <th style="padding:8px;border-bottom:1px solid #e5e5e5;text-align:left;">
                                        Reaksi
                                    </th>
                                    <th style="padding:8px;border-bottom:1px solid #e5e5e5;text-align:left;">
                                        Waktu
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($post->reactions as $reaction)
                                    <tr>
                                        <td style="padding:6px 8px;border-bottom:1px solid #f1f1f1;">
                                            {{ $reaction->user->name ?? '-' }}
                                        </td>
                                        <td style="padding:6px 8px;border-bottom:1px solid #f1f1f1;">
                                            @if($reaction->reaction === 1)
                                                <span style="color:#0d6efd;">
                                                    <i class="fa fa-thumbs-up"></i> Suka
                                                </span>
                                            @else
                                                <span style="color:#dc3545;">
                                                    <i class="fa fa-thumbs-down"></i> Tidak Suka
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding:6px 8px;border-bottom:1px solid #f1f1f1;">
                                            {{ ($reaction->updated_at ?? $reaction->created_at)->format('d M Y, H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="padding:8px;text-align:center;color:#999;">
                                            Belum ada yang memberi reaksi pada postingan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    {{-- Pagination --}}
    <div style="margin-top:18px;">
        @if(method_exists($posts, 'links'))
            {{ $posts->links() }}
        @endif
    </div>

</div>
@endsection

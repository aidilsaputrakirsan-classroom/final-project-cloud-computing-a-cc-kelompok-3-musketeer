@extends('layouts.main')

@section('title', 'Reaksi Postingan | Chatter Box')

@section('content')
@php
    $allReactions = $post->reactions ?? collect();

    $likes    = $allReactions->where('reaction', 1);
    $dislikes = $allReactions->where('reaction', -1);
@endphp

<div class="container" style="padding: 20px; max-width: 820px; margin:0 auto;">

    {{-- Tombol kembali ke halaman Daftar Suka --}}
    <a href="{{ session('reaction_detail_back_url') ?? route('user.reactions.index') }}"
       style="display:inline-block;margin-bottom:14px;text-decoration:none;color:#40A09C;">
        ← Kembali
    </a>

    <h2 style="margin: 6px 0 18px 0; font-weight:600; color:#314057;">
        Reaksi Postingan:
        <span style="color:#40A09C;">{{ $post->title }}</span>
    </h2>

    {{-- Ringkasan total suka & tidak suka --}}
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:18px;">
        <div style="background:#fff;padding:12px 16px;border-radius:8px;
                    box-shadow:0 1px 6px rgba(0,0,0,0.04);min-width:140px;">
            <div style="font-size:1.15em;font-weight:700;color:#0d6efd;">
                {{ $likes->count() }}
            </div>
            <div style="color:#666;font-size:0.9em;">Total Suka</div>
        </div>

        <div style="background:#fff;padding:12px 16px;border-radius:8px;
                    box-shadow:0 1px 6px rgba(0,0,0,0.04);min-width:140px;">
            <div style="font-size:1.15em;font-weight:700;color:#dc3545;">
                {{ $dislikes->count() }}
            </div>
            <div style="color:#666;font-size:0.9em;">Total Tidak Suka</div>
        </div>
    </div>

    {{-- DAFTAR REAKSI --}}
    @if($allReactions->isEmpty())
        <div style="padding: 18px;background: #f8f9fa;border-radius: 8px;
                    text-align: center;color: #666;">
            Belum ada yang memberikan reaksi pada postingan ini.
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($allReactions as $r)
                <div style="background:#fff;padding:12px;border-radius:8px;
                            display:flex;align-items:center;gap:12px;
                            box-shadow:0 1px 4px rgba(0,0,0,0.03);">

                    <div style="width:44px;height:44px;border-radius:50%;
                                background:#40A09C;display:flex;align-items:center;
                                justify-content:center;color:#fff;font-weight:700;">
                        {{ strtoupper(substr($r->user->name ?? 'U', 0, 1)) }}
                    </div>

                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;color:#314057;">
                            {{ $r->user->name ?? 'User' }}
                        </div>
                        <div style="color:#777;font-size:0.92em;margin-top:4px;">
                            @if($r->reaction == 1)
                                <span style="color:#0d6efd;font-weight:700;">
                                    <i class="fa fa-thumbs-up"></i> Suka
                                </span>
                            @else
                                <span style="color:#dc3545;font-weight:700;">
                                    <i class="fa fa-thumbs-down"></i> Tidak Suka
                                </span>
                            @endif

                            <span style="margin-left:10px;color:#999;font-size:0.85em;">
                                • {{ $r->updated_at?->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <div style="text-align:right;">
                        @if($r->user)
                            <a href="{{ route('profile.show', $r->user->id) }}"
                                style="text-decoration:none;color:#40A09C;font-weight:600;">
                                Lihat Profil
                            </a>
                        @else
                            <span style="color:#999;">(akun dihapus)</span>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

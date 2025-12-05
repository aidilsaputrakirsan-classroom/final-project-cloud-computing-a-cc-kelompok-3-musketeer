{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.main')

@section('title', ($user->name ?? 'Profil') . ' | Chatter Box')

@section('content')
<div style="max-width:1000px;margin:0 auto;padding:20px;">

    <a href="{{ session('profile_back_url') ?? url('/daftar-suka') }}"
       style="color:#40A09C;text-decoration:none;display:inline-block;margin-bottom:14px;">
       ← Kembali
    </a>

    <div style="display:flex;align-items:center;gap:16px;margin-top:10px;">
        <div style="width:72px;height:72px;border-radius:50%;background:#40A09C;background-size:cover;background-position:center;color:#fff;
                    display:flex;align-items:center;justify-content:center;font-size:1.6em;font-weight:bold;{{ $user->profile_picture ? "background-image:url('" . e(Storage::url($user->profile_picture)) . "');" : '' }}">
            @unless($user->profile_picture)
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            @endunless
        </div>

        <div>
            <h2 style="margin:0">{{ $user->name }}</h2>
            <div style="color:#666;">{{ $user->email }}</div>
            <div style="margin-top:6px;color:#777;font-size:0.95em;">
                Total postingan: {{ $user->posts()->count() }}
            </div>
        </div>
    </div>

    <hr style="margin:18px 0;">

    <h3 style="margin-bottom:10px;">Postingan oleh {{ $user->name }}</h3>

    <div style="display:flex;flex-direction:column;gap:12px;">
        @forelse($posts as $p)
            <div style="background:#fff;padding:12px;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                <a href="{{ route('posts.show', $p) }}"
                   style="font-weight:700;color:#2b3d4f;text-decoration:none;">
                    {{ $p->title }}
                </a>
                <div style="color:#666;font-size:0.92em;margin-top:5px;">
                    {{ \Illuminate\Support\Str::limit($p->content, 180) }}
                </div>
            </div>
        @empty
            <div style="color:#777">Belum ada postingan dari user ini.</div>
        @endforelse
    </div>

    <div style="margin-top:12px;">
        {{ $posts->links() }}
    </div>
</div>
@endsection

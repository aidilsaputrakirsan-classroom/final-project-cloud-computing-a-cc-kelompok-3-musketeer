@extends('layouts.main')

@section('title', 'Dashboard | Chatter Box')

@section('content')
    @php
        $user = auth()->user();
    @endphp

    <section class="dashboard-content" style="flex:1;padding:16px 30px 24px 0;min-width:0;margin-left:0;">
        <div class="dashboard-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <input type="text" class="search-bar" placeholder="Cari postingan ngetren saat ini?" style="width:270px;padding:7px 10px;border:1px solid #ddd;border-radius:7px;font-size:0.99em;background:#fff;">
            
            <div class="user-info" style="display:flex;align-items:center;gap:15px;">
                <i class="fa fa-bell" style="font-size:1.1em;color:#aaa;cursor:pointer;"></i>
                
                {{-- Avatar --}}
                @if($user)
                    <a href="{{ route('profile.edit') }}" style="text-decoration:none;">
                        <div 
                            class="user-avatar" 
                            style="width:32px;height:32px;border-radius:50%;
                                   background:#40A09C;background-size:cover;background-position:center;
                                   cursor:pointer;display:flex;align-items:center;justify-content:center;
                                   color:#fff;font-weight:bold;font-size:0.9em;
                                   {{ $user->profile_picture 
                                        ? "background-image:url('" . e(Storage::url($user->profile_picture)) . "');" 
                                        : '' }}">
                            @unless($user->profile_picture)
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            @endunless
                        </div>
                    </a>
                @endif

                {{-- Logout button --}}
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" title="Logout" style="
                        background:#dc3545;
                        color:#fff;
                        border:none;
                        border-radius:6px;
                        padding:6px 10px;
                        font-size:0.9em;
                        cursor:pointer;
                        display:flex;
                        align-items:center;
                        gap:5px;
                    ">
                        <i class="fa fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="content-list-controls" style="display:flex;align-items:center;gap:7px;margin-bottom:8px;margin-top:5px;">
            <button class="btn-filter" style="background:#e7f6fa;color:#40a09c;border:none;padding:6px 17px;border-radius:16px;font-size:0.97em;cursor:pointer;">Baru</button>
            <button class="btn-category" style="background:#e7f6fa;color:#40a09c;border:none;padding:6px 17px;border-radius:16px;font-size:0.97em;cursor:pointer;">
                <i class="fa fa-filter"></i> Kategori
            </button>
            <a href="{{ route('posts.create') }}" class="btn-post" style="background:#40A09C;color:#fff;border:none;padding:7px 17px;border-radius:7px;font-size:0.97em;font-weight:500;margin-left:auto;text-decoration:none;display:inline-block;">+ Buat Postingan</a>
        </div>

        @if(session('success'))
            <div style="padding:12px 16px;border-radius:7px;margin-bottom:20px;background:#d4edda;color:#155724;border:1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="padding:12px 16px;border-radius:7px;margin-bottom:20px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        <div class="cards-list" style="display:flex;flex-direction:column;gap:5px;margin-top:12px;">
            @forelse($posts as $post)
                <div class="post-card" style="
                    background:#fff;
                    border-radius:10px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.05);
                    padding:20px 22px;
                    transition:transform 0.2s ease, box-shadow 0.2s ease;
                ">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <div style="
                            width:40px;
                            height:40px;
                            border-radius:50%;
                            background:#ddd;
                            background-size:cover;
                            background-position:center;
                        "></div>
                        <div>
                            <div style="font-weight:600;color:#d49d3d;font-size:1em;">{{ $post->user->name }}</div>
                            <div style="font-size:0.85em;color:#888;">{{ $post->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <div style="margin-top:5px;margin-bottom:8px;">

                        {{-- Judul Postingan --}}
                        <a href="{{ route('posts.show', $post) }}" style="
                            font-weight:700;
                            font-size:1.05em;
                            color:#2b3d4f;
                            text-decoration:none;
                            display:block;
                            margin-bottom:4px;
                        ">{{ $post->title }}</a>

                        {{-- Isi Konten Postingan --}}
                        <p style="font-size:0.95em;color:#4a5568;margin:0 0 10px;">
                            <a href="{{ route('posts.show', $post) }}" style="color:inherit;text-decoration:none;display:block;">
                                {{ \Illuminate\Support\Str::limit($post->content, 150) }}
                            </a>
                        </p>
                    </div>

                    {{-- Icon Komentar --}}
                    <div style="display:flex;align-items:center;gap:16px;font-size:0.9em;color:#666;margin-bottom:10px;">
                        <span><i class="fa fa-eye"></i> {{ $post->views }}</span>

                        <span>
                            <a href="{{ route('posts.show', $post) }}" style="color:inherit;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                                <i class="fa fa-comment"></i> <span>{{ $post->comments_count }}</span>
                            </a>
                        </span>

                        <span><i class="fa fa-thumbs-up"></i> {{ $post->likes }}</span>
                        <span><i class="fa fa-thumbs-down"></i> {{ $post->dislikes }}</span>
                    </div>

                    @if($post->category)
                        <div style="margin-bottom:12px;">
                            <span style="
                                display:inline-block;
                                background:#40A09C;
                                color:#fff;
                                padding:5px 14px;
                                border-radius:20px;
                                font-size:0.88em;
                            ">{{ $post->category }}</span>
                        </div>
                    @endif

                    @if(Auth::id() === $post->user_id)
                        <div style="display:flex;gap:10px;padding-top:10px;border-top:1px solid #eee;">
                            <a href="{{ route('posts.edit', $post) }}" style="
                                padding:7px 15px;
                                border-radius:6px;
                                font-size:0.9em;
                                text-decoration:none;
                                background:#40A09C;
                                color:#fff;
                                display:flex;
                                align-items:center;
                                gap:5px;
                            ">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="
                                    padding:7px 15px;
                                    border-radius:6px;
                                    font-size:0.9em;
                                    background:#dc3545;
                                    color:#fff;
                                    border:none;
                                    cursor:pointer;
                                    display:flex;
                                    align-items:center;
                                    gap:5px;
                                " onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="post-card" style="background:#fff;border-radius:7px;box-shadow:0 1px 6px rgba(0,0,0,0.05);padding:20px;text-align:center;">
                    <p style="color:#999;">Belum ada postingan. <a href="{{ route('posts.create') }}" style="color:#40A09C;">Buat postingan pertama Anda!</a></p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div style="display:flex;justify-content:center;gap:10px;margin-top:0px;">
                {{ $posts->links() }}
            </div>
        @endif
    </section>

    <style>
        .post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .post-card a {
            color: inherit;
            text-decoration: none;
        }
    </style>
@endsection

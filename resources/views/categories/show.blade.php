@extends('layouts.main')

@section('title', $category->name . ' | Chatter Box')

@section('content')
<div style="
    padding:24px; 
    font-family: 'Montserrat', Arial, sans-serif;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div>
            <h1 style="font-size:1.8rem;margin:0;">Kategori {{ $category->name }}</h1>
            <div style="color:#666;margin-top:6px;">Berikut postingan pada kategori {{ $category->name }}</div>
        </div>
        <div style="text-align:right;color:#888;">
            <div>{{ $category->posts()->count() }} Postingan</div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:20px;margin-top:8px;">
        @forelse($posts as $post)
            <div class="post-card" style="
                background:#fff;
                border-radius:10px;
                box-shadow:0 2px 8px rgba(0,0,0,0.05);
                padding:18px 20px;
                transition:transform 0.2s ease, box-shadow 0.2s ease;
            ">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                    {{-- avatar placeholder --}}
                    <div style="
                        width:40px;
                        height:40px;
                        border-radius:50%;
                        background:#ddd;
                        background-size:cover;
                        background-position:center;
                        flex-shrink:0;
                    "></div>

                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;color:#d49d3d;font-size:1em;">{{ $post->user->name }}</div>
                        <div style="font-size:0.85em;color:#888;">{{ $post->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                <div style="margin-top:5px;margin-bottom:8px;">
                    {{-- Judul --}}
                    <a href="{{ route('posts.show', $post) }}" style="
                        font-weight:700;
                        font-size:1.05em;
                        color:#2b3d4f;
                        text-decoration:none;
                        display:block;
                        margin-bottom:6px;
                    ">{{ $post->title }}</a>

                    {{-- Konten --}}
                    <p style="font-size:0.95em;color:#4a5568;margin:0 0 10px;">
                        <a href="{{ route('posts.show', $post) }}" style="color:inherit;text-decoration:none;display:block;">
                            {{ \Illuminate\Support\Str::limit($post->content, 150) }}
                        </a>
                    </p>
                </div>

                {{-- Statistik --}}
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

                {{-- Tombol Edit / Hapus --}}
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
                <p style="color:#999;">Belum ada postingan pada kategori ini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($posts->hasPages())
        <div style="display:flex;justify-content:center;gap:10px;margin-top:18px;">
            {{ $posts->links() }}
        </div>
    @endif
</div>

<style>
.post-card:hover, .post-card:focus {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
.post-card a { color: inherit; text-decoration: none; }
</style>
@endsection

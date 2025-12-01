{{-- resources/views/components/post-card.blade.php --}}
<div class="post-card" id="post-{{ $post->id }}" style="border:1px solid #eee;padding:1rem;border-radius:6px;margin-bottom:1rem;">
    <h3 style="margin:0 0 .5rem;"><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
    <p style="margin:0 0 .5rem;color:#555;">oleh {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}</p>
    <p style="margin:0 0 .75rem;color:#333;">{{ \Illuminate\Support\Str::limit($post->body, 200) }}</p>

    <div style="display:flex;gap:.5rem;align-items:center;">
        <a href="{{ route('posts.show', $post) }}" class="btn">Baca</a>

        {{-- link menuju anchor komentar di halaman detail --}}
        <a href="{{ route('posts.show', $post) }}#comments" class="btn">Komentar ({{ $post->comments_count ?? $post->comments->count() }})</a>

        @auth
            {{-- tombol toggle inline form --}}
            <button class="btn btn-comment-toggle" data-post-id="{{ $post->id }}" type="button">Tulis Komentar</button>
        @else
            <a href="{{ route('login') }}" class="btn">Login</a>
        @endauth
    </div>

    {{-- inline comment form (disembunyikan default) --}}
    @auth
    <div class="inline-comment-form" id="inline-comment-form-{{ $post->id }}" style="display:none;margin-top:.75rem;">
        <form class="inline-comment-form__form" action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <textarea name="body" rows="2" placeholder="Tulis komentar..." required style="width:100%;padding:.4rem;border:1px solid #ddd;border-radius:4px;"></textarea>
            <div style="margin-top:.4rem;">
                <button type="submit" class="btn">Kirim</button>
                <button type="button" class="btn btn-cancel" data-post-id="{{ $post->id }}">Batal</button>
            </div>
        </form>
    </div>
    @endauth
</div>

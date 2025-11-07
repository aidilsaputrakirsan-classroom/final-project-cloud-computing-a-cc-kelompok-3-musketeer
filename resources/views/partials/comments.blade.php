{{-- resources/views/partials/comments.blade.php --}}
<div class="comments-section" style="margin-top:1.5rem;">
    {{-- Form komentar --}}
    <div class="comment-form" style="margin-bottom:1rem;">
        @auth
            <form id="comment-form" action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <div>
                    <textarea name="body" id="comment-body" rows="3" placeholder="Tulis komentar..." required
                        style="width:100%;padding:.5rem;border:1px solid #ddd;border-radius:4px;">{{ old('body') }}</textarea>
                    @error('body') <div class="text-red-600">{{ $message }}</div> @enderror
                </div>
                <div style="margin-top:.5rem;">
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        @else
            <p>Silakan <a href="{{ route('login') }}">login</a> untuk mengomentari.</p>
        @endauth
    </div>

    {{-- List komentar --}}
    <div id="comments-list">
        @forelse($post->comments()->with('user')->get() as $comment)
            <div id="comment-{{ $comment->id }}" style="padding:.6rem 0;border-bottom:1px solid #f0f0f0;">
                <p style="margin:0 0 .25rem;">
                    <strong>{{ $comment->user->name }}</strong>
                    <small style="color:#666;"> · {{ $comment->created_at->diffForHumans() }}</small>
                </p>
                <div style="white-space:pre-wrap;margin-bottom:.5rem;">{{ $comment->body }}</div>

                @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->id() === $post->user_id))
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Hapus komentar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:transparent;border:0;color:#e3342f;cursor:pointer;padding:0;">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p style="color:#666;">Belum ada komentar. Jadilah yang pertama!</p>
        @endforelse
    </div>
</div>


<div id="comments" class="comments-section">
    <h3>Komentar ({{ $post->comments_count ?? $post->comments->count() }})</h3>

    {{-- Form komentar (top-level) --}}
    <div class="comment-form" style="margin: 12px 0 18px;">
        @auth
            <form id="comment-form" action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="body" id="comment-body" rows="4" placeholder="Tulis komentar..." required>{{ old('body') }}</textarea>
                @error('body')
                    <div class="small-muted" style="color:#e3342f;margin-top:6px;">{{ $message }}</div>
                @enderror
                <div style="margin-top:8px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </form>
        @else
            <p class="small-muted">Silakan <a href="{{ route('login') }}">login</a> untuk mengomentari.</p>
        @endauth
    </div>

    {{-- List komentar (top-level) --}}
    <div id="comments-list">
        @php
            if (!isset($comments)) {
                $comments = $post->comments()->whereNull('parent_id')->with('user','children.user')->latest()->get();
            }
        @endphp

        @forelse($comments as $comment)
            <div id="comment-{{ $comment->id }}" class="comment-item">
                <p class="comment-meta" style="margin:0;">
                    <strong>{{ $comment->user->name }}</strong>
                    <span class="small-muted"> · {{ $comment->created_at->diffForHumans() }}</span>
                </p>

                {{-- tampilkan body --}}
                <div class="comment-body" data-original-body>{{ $comment->body }}</div>

                {{-- actions: edit (owner only, allowed within 1 minute) + delete + reply --}}
                @auth
                    @php
                        $isOwner = auth()->id() === $comment->user_id;
                        $isPostOwner = auth()->id() === $post->user_id;
                        $canEdit = $isOwner && $comment->created_at->greaterThan(now()->subMinute());
                    @endphp

                    <div style="margin-top:6px;">
                        @if($isOwner)
                            <button type="button"
                                    class="btn btn-secondary btn-comment-edit"
                                    data-comment-id="{{ $comment->id }}"
                                    @if(!$canEdit) disabled title="Waktu edit sudah lewat (1 menit)" @endif
                                    style="padding:6px 10px;font-size:.9em;">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                        @endif

                        @if($isOwner || $isPostOwner)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;margin-left:6px;" onsubmit="return confirm('Hapus komentar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:.9em;">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        @endif

                        {{-- Reply button (any authenticated user) --}}
                        <button type="button" class="btn btn-primary btn-reply-toggle" data-comment-id="{{ $comment->id }}" style="padding:6px 10px;font-size:.9em;margin-left:6px;">
                            <i class="fa fa-reply"></i> Balas
                        </button>
                    </div>

                    {{-- Inline edit form (hidden) --}}
                    @if($isOwner)
                        <div class="comment-edit-form-wrap" id="comment-edit-form-{{ $comment->id }}" style="display:none;margin-top:10px;">
                            <form action="{{ route('comments.update', $comment) }}" method="POST" class="comment-edit-form">
                                @csrf
                                @method('PUT')
                                <textarea name="body" rows="3" style="width:100%;padding:.5rem;border:1px solid #ddd;border-radius:5px;">{{ $comment->body }}</textarea>
                                <div style="margin-top:6px;">
                                    <button type="submit" class="btn btn-primary" style="padding:6px 10px;font-size:.9em;">Simpan</button>
                                    <button type="button" class="btn btn-secondary btn-edit-cancel" data-comment-id="{{ $comment->id }}" style="padding:6px 10px;font-size:.9em;margin-left:6px;">Batal</button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- Reply form (hidden) --}}
                    <div class="comment-reply-wrap" id="comment-reply-{{ $comment->id }}" style="display:none;margin-top:10px;">
                        <form action="{{ route('comments.store') }}" method="POST" class="comment-reply-form">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <textarea name="body" rows="2" placeholder="Tulis balasan..." style="width:100%;padding:.45rem;border:1px solid #ddd;border-radius:5px;"></textarea>
                            <div style="margin-top:6px;">
                                <button type="submit" class="btn btn-primary" style="padding:6px 10px;font-size:.9em;">Kirim</button>
                                <button type="button" class="btn btn-secondary btn-reply-cancel" data-comment-id="{{ $comment->id }}" style="padding:6px 10px;font-size:.9em;margin-left:6px;">Batal</button>
                            </div>
                        </form>
                    </div>
                @endauth

                {{-- Tampilkan replies (children) --}}
                @if($comment->relationLoaded('children') ? $comment->children->isNotEmpty() : $comment->children()->exists())
                    <div class="replies">
                        @foreach($comment->children as $reply)
                            <div id="comment-{{ $reply->id }}" style="margin-bottom:12px;">
                                <p style="margin:0;">
                                    <strong>{{ $reply->user->name }}</strong>
                                    <span class="small-muted"> · {{ $reply->created_at->diffForHumans() }}</span>
                                </p>
                                <div class="comment-body">{{ $reply->body }}</div>

                                @auth
                                    @php
                                        $isReplyOwner = auth()->id() === $reply->user_id;
                                        $canEditReply = $isReplyOwner && $reply->created_at->greaterThan(now()->subMinute());
                                    @endphp
                                    <div style="margin-top:6px;">
                                        @if($isReplyOwner)
                                            <button type="button" class="btn btn-secondary btn-comment-edit" data-comment-id="{{ $reply->id }}" @if(!$canEditReply) disabled title="Waktu edit sudah lewat (1 menit)" @endif style="padding:6px 10px;font-size:.9em;">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        @endif

                                        @if($isReplyOwner || auth()->id() === $post->user_id)
                                            <form action="{{ route('comments.destroy', $reply) }}" method="POST" style="display:inline;margin-left:6px;" onsubmit="return confirm('Hapus komentar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:.9em;">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- Inline edit form for reply --}}
                                    @if($isReplyOwner)
                                        <div class="comment-edit-form-wrap" id="comment-edit-form-{{ $reply->id }}" style="display:none;margin-top:10px;">
                                            <form action="{{ route('comments.update', $reply) }}" method="POST" class="comment-edit-form">
                                                @csrf
                                                @method('PUT')
                                                <textarea name="body" rows="2" style="width:100%;padding:.45rem;border:1px solid #ddd;border-radius:5px;">{{ $reply->body }}</textarea>
                                                <div style="margin-top:6px;">
                                                    <button type="submit" class="btn btn-primary" style="padding:6px 10px;font-size:.9em;">Simpan</button>
                                                    <button type="button" class="btn btn-secondary btn-edit-cancel" data-comment-id="{{ $reply->id }}" style="padding:6px 10px;font-size:.9em;margin-left:6px;">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="comments-empty">Belum ada komentar. Jadilah yang pertama!</p>
        @endforelse
    </div>
</div>

{{-- Script khusus komentar (disertakan di partial supaya behavior tetap terpisah) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle edit form
    document.querySelectorAll('.btn-comment-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            const id = this.dataset.commentId;
            const wrap = document.getElementById('comment-edit-form-' + id);
            if (!wrap) return;
            wrap.style.display = wrap.style.display === 'none' ? 'block' : 'none';
            const ta = wrap.querySelector('textarea');
            if (ta) ta.focus();
        });
    });

    // Cancel edit
    document.querySelectorAll('.btn-edit-cancel').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.commentId;
            const wrap = document.getElementById('comment-edit-form-' + id);
            if (wrap) wrap.style.display = 'none';
        });
    });

    // Toggle reply forms
    document.querySelectorAll('.btn-reply-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.commentId;
            const wrap = document.getElementById('comment-reply-' + id);
            if (!wrap) return;
            wrap.style.display = wrap.style.display === 'none' ? 'block' : 'none';
            const ta = wrap.querySelector('textarea');
            if (ta) ta.focus();
        });
    });

    // Cancel reply
    document.querySelectorAll('.btn-reply-cancel').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.commentId;
            const wrap = document.getElementById('comment-reply-' + id);
            if (wrap) wrap.style.display = 'none';
        });
    });
});
</script>

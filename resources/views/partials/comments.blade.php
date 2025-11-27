<style>
    /* Hover untuk semua tombol */
    button:hover:not(:disabled) {
        opacity: .85;
        transform: translateY(-1px);
    }

    /* Disabled = abu-abu */
    button:disabled {
        background: #bfbfbf !important;
        color: #eee !important;
        cursor: not-allowed !important;
        opacity: 0.7;
    }
</style>

<div id="comments" class="comments-section" style="margin-top:28px;">
    <h3 style="font-size:1.23em; font-weight:700; color:#40A09C; margin-bottom:18px;">
        Komentar ({{ $post->comments_count ?? $post->comments->count() }})
    </h3>

    {{-- Form komentar --}}
    <div class="comment-form" style="margin:18px 0 24px;">
        @auth
            <form id="comment-form" action="{{ route('comments.store') }}" method="POST"
                  style="background:#f9f9f9;padding:14px 18px 8px 18px;border-radius:10px;box-shadow:0 1px 6px rgba(40,70,90,0.06);">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">

                <textarea name="body" id="comment-body" rows="5"
                    style="width:100%;min-height:90px;padding:13px;font-size:1em;border-radius:7px;border:1px solid #bbb;
                    background:#fff;resize:vertical;margin-bottom:9px;box-shadow:0 1px 4px rgba(40,70,90,.03);"
                    placeholder="Tulis komentar..." required>{{ old('body') }}</textarea>

                @error('body')
                    <div style="color:#e3342f;margin-top:6px;">{{ $message }}</div>
                @enderror

                <div style="margin-top:8px;text-align:right;">
                    <button type="submit"
                        style="background:#40A09C;color:#fff;border:none;padding:7px 20px;border-radius:7px;
                               font-size:1em;font-weight:500;cursor:pointer;transition:.18s;">
                        <i class="fa fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </form>
        @else
            <p style="color:#888;">Silakan <a href="{{ route('login') }}">login</a> untuk mengomentari.</p>
        @endauth
    </div>

    {{-- List komentar --}}
    <div id="comments-list">
        @php
            if (!isset($comments)) {
                $comments = $post->comments()
                    ->whereNull('parent_id')
                    ->with('user','children.user')
                    ->latest()->get();
            }
        @endphp

        @forelse($comments as $comment)
            <div id="comment-{{ $comment->id }}"
                 style="background:#f8f9fa;border-radius:10px;padding:13px 18px 10px 18px;
                        box-shadow:0 1px 6px rgba(0,0,0,0.05);margin-bottom:18px;transition:.18s;">
                
                <div style="display:flex;align-items:center;gap:13px;">
                    <div style="width:33px;height:33px;border-radius:50%;background:#40A09C;
                                display:flex;align-items:center;justify-content:center;color:#fff;
                                font-weight:bold;font-size:1.02em;">
                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                    </div>

                    <div>
                        <span style="font-weight:600;color:#2b3d4f;">{{ $comment->user->name }}</span>
                        <span style="color:#999;font-size:0.92em;">
                            · {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <div style="margin:11px 0 5px;font-size:1em;color:#474747;">
                    {{ $comment->body }}
                </div>

                @auth
                    @php
                        $isOwner = auth()->id() === $comment->user_id;
                        $isPostOwner = auth()->id() === $post->user_id;
                        $canEdit = $isOwner && $comment->created_at->greaterThan(now()->subMinute());
                    @endphp

                    <div style="margin-top:7px;display:flex;gap:9px;">

                        {{-- EDIT BUTTON --}}
                        @if($isOwner)
                            <button type="button"
                                    class="btn-comment-edit"
                                    data-comment-id="{{ $comment->id }}"
                                    @if(!$canEdit) disabled title="Waktu edit sudah lewat (1 menit)" @endif
                                    style="display:inline-flex;align-items:center;gap:7px;
                                           padding:5px 13px;background:#40A09C;color:#fff;border:none;
                                           border-radius:9px;font-size:.98em;font-weight:500;cursor:pointer;
                                           transition:.18s;">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                        @endif

                        {{-- DELETE BUTTON --}}
                        @if($isOwner || $isPostOwner)
                            <form action="{{ route('comments.destroy', $comment) }}"
                                  method="POST" style="display:inline;"
                                  onsubmit="return confirm('Hapus komentar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="display:inline-flex;align-items:center;gap:7px;background:#dc3545;
                                               color:#fff;padding:5px 13px;border:none;border-radius:8px;
                                               font-size:.98em;cursor:pointer;transition:.18s;">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        @endif

                        {{-- REPLY BUTTON --}}
                        <button type="button"
                                class="btn-reply-toggle"
                                data-comment-id="{{ $comment->id }}"
                                style="display:inline-flex;align-items:center;gap:8px;padding:5px 13px;
                                       background:#ffc107;color:#333;border:none;border-radius:8px;
                                       font-size:.99em;cursor:pointer;transition:.18s;">
                            <i class="fa fa-reply"></i> Balas
                        </button>
                    </div>

                    {{-- EDIT FORM --}}
                    @if($isOwner)
                        <div id="comment-edit-form-{{ $comment->id }}" style="display:none;margin-top:13px;">
                            <form action="{{ route('comments.update', $comment) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <textarea name="body" rows="3"
                                    style="width:100%;padding:.62rem;border:1px solid #bbb;border-radius:7px;
                                           background:#fff;font-size:1em;min-height:70px;"></textarea>

                                <div style="margin-top:6px;text-align:right;">
                                    <button type="submit"
                                        style="padding:5px 13px;font-size:.97em;cursor:pointer;transition:.18s;">
                                        Simpan
                                    </button>

                                    <button type="button"
                                        class="btn-edit-cancel"
                                        data-comment-id="{{ $comment->id }}"
                                        style="padding:5px 12px;margin-left:7px;font-size:.97em;cursor:pointer;transition:.18s;">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- REPLY FORM --}}
                    <div id="comment-reply-{{ $comment->id }}" style="display:none;margin-top:12px;">
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                            <textarea name="body" rows="2"
                                style="width:100%;min-height:55px;padding:.62rem;border:1px solid #bbb;
                                       border-radius:7px;background:#fff;font-size:0.99em;"></textarea>

                            <div style="margin-top:5px;text-align:right;">
                                <button type="submit"
                                        style="padding:5px 13px;font-size:.97em;cursor:pointer;transition:.18s;">
                                    Kirim
                                </button>

                                <button type="button"
                                        class="btn-reply-cancel"
                                        data-comment-id="{{ $comment->id }}"
                                        style="padding:5px 12px;margin-left:7px;font-size:.97em;
                                               cursor:pointer;transition:.18s;">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                @endauth

                {{-- CHILD REPLIES --}}
                @if($comment->children->isNotEmpty())
                    <div style="margin-top:13px;margin-left:35px;">
                        @foreach($comment->children as $reply)
                            <div id="comment-{{ $reply->id }}"
                                 style="margin-bottom:11px;background:#fbfefd;border-radius:9px;padding:8px 13px;">

                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#40A09C;
                                                display:flex;align-items:center;justify-content:center;color:#fff;
                                                font-weight:bold;font-size:.97em;">
                                        {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div>
                                        <span style="font-weight:600;color:#314057;">{{ $reply->user->name }}</span>
                                        <span style="color:#888;"> · {{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div style="margin:6px 0 4px 0;">{{ $reply->body }}</div>

                                @auth
                                    @php
                                        $isReplyOwner = auth()->id() === $reply->user_id;
                                        $canEditReply = $isReplyOwner && $reply->created_at->greaterThan(now()->subMinute());
                                    @endphp

                                    <div style="margin-top:5px;display:flex;gap:7px;">
                                        @if($isReplyOwner)
                                            <button type="button"
                                                class="btn-comment-edit"
                                                data-comment-id="{{ $reply->id }}"
                                                @if(!$canEditReply) disabled @endif
                                                style="padding:4px 11px;background:#40A09C;color:#fff;border:none;
                                                       border-radius:8px;font-size:.95em;cursor:pointer;">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        @endif

                                        @if($isReplyOwner || auth()->id() === $post->user_id)
                                            <form action="{{ route('comments.destroy', $reply) }}"
                                                  method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Hapus komentar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    style="padding:4px 11px;background:#dc3545;color:#fff;border:none;
                                                           border-radius:8px;font-size:.95em;cursor:pointer;">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        @empty
            <p style="color:#999;font-size:.97em;">Belum ada komentar. Jadilah yang pertama!</p>
        @endforelse
    </div>
</div>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-comment-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            const wrap = document.getElementById('comment-edit-form-' + this.dataset.commentId);
            if (!wrap) return;
            wrap.style.display = (wrap.style.display === 'none' || wrap.style.display === '') ? 'block' : 'none';
        });
    });

    document.querySelectorAll('.btn-edit-cancel').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('comment-edit-form-' + this.dataset.commentId).style.display = 'none';
        });
    });

    document.querySelectorAll('.btn-reply-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const wrap = document.getElementById('comment-reply-' + this.dataset.commentId);
            wrap.style.display = (wrap.style.display === 'none' || wrap.style.display === '') ? 'block' : 'none';
        });
    });

    document.querySelectorAll('.btn-reply-cancel').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('comment-reply-' + this.dataset.commentId).style.display = 'none';
        });
    });
});
</script>

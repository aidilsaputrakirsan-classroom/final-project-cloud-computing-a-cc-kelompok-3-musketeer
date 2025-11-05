<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | Chatter Box</title>
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 7px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        .post-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .post-author-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #40A09C;
        }
        .author-name {
            font-weight: 600;
            color: #d49d3d;
            font-size: 1em;
        }
        .post-meta {
            font-size: 0.9em;
            color: #999;
            margin-bottom: 15px;
        }
        .post-title {
            font-weight: 600;
            font-size: 1.5em;
            color: #4b5d6b;
            margin-bottom: 15px;
        }
        .post-content {
            font-size: 1.05em;
            color: #314057;
            line-height: 1.6;
            margin-bottom: 20px;
            white-space: pre-wrap;
        }
        .post-stats {
            display: flex;
            gap: 20px;
            font-size: 1em;
            color: #787878;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 7px;
        }
        .post-categories {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }
        .post-category-badge {
            padding: 5px 14px;
            background: #40A09C;
            color: #fff;
            border-radius: 5px;
            font-size: 0.91em;
        }
        .post-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 7px;
            font-size: 0.97em;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            border: none;
            transition: background 0.2s;
        }
        .btn-primary {
            background: #40A09C;
            color: #fff;
        }
        .btn-primary:hover {
            background: #278a84;
        }
        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-danger {
            background: #dc3545;
            color: #fff;
        }
        .btn-danger:hover {
            background: #c82333;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>
<div class="container">
    <div class="post-header">
        <div class="post-author-row">
            <div class="author-avatar"></div>
            <div class="author-name">{{ $post->user->name }}</div>
        </div>
        <div class="post-meta">
            Dipublikasikan pada {{ $post->created_at->format('d F Y, H:i') }}
            @if($post->updated_at != $post->created_at)
                • Diperbarui pada {{ $post->updated_at->format('d F Y, H:i') }}
            @endif
        </div>
        <div class="post-title">{{ $post->title }}</div>
    </div>

    <div class="post-content">{{ $post->content }}</div>

    <div class="post-stats">
        <span><i class="fa fa-eye"></i> {{ $post->views }} Dilihat</span>
        <span><i class="fa fa-comment"></i> {{ $post->comments_count }} Komentar</span>
        <span><i class="fa fa-thumbs-up"></i> {{ $post->likes }} Suka</span>
        <span><i class="fa fa-thumbs-down"></i> {{ $post->dislikes }} Tidak Suka</span>
    </div>

    @if($post->category)
        <div class="post-categories">
            <span class="post-category-badge">{{ $post->category }}</span>
        </div>
    @endif

    <div class="post-actions">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        @if(Auth::id() === $post->user_id)
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">
                <i class="fa fa-edit"></i> Edit Postingan
            </a>
            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                    <i class="fa fa-trash"></i> Hapus Postingan
                </button>
            </form>
        @endif
    </div>
</div>
</body>
</html>


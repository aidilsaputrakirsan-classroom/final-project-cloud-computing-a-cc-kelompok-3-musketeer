<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Chatter Box</title>
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
        }
        .main-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            background: #fff;
            width: 230px;
            padding: 0;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo-row {
            display: flex;
            align-items: center;
            padding: 16px 0 10px 18px;
            width: 100%;
            gap: 10px;
        }
        .logo-row img {
            height: 36px;
            margin-right: 8px;
        }
        .logo-title {
            font-size: 1.22em;
            font-weight: bold;
            color: #40A09C;
            letter-spacing: 0.04em;
        }
        .logo-sub {
            margin-top: -4px;
            font-size: 0.86em;
            color: #855c4b;
        }
        .menu-list {
            list-style: none;
            padding: 0 4px;
            width: 100%;
        }
        .menu-list li {
            padding: 8px 20px;
            color: #3b454b;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 0.97em;
        }
        .menu-list li.active,
        .menu-list li:hover {
            background: #e7f6fa;
            border-radius: 6px;
            color: #40A09C;
        }
        .menu-list li a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .menu-list li i {
            margin-right: 10px;
            font-size: 1.07em;
        }
        .dashboard-content {
            flex: 1;
            padding: 16px 22px 24px 22px;
            min-width: 0;
        }
        .profile-header {
            background: #fff;
            border-radius: 7px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 25px;
        }
        .profile-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #40A09C;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2em;
            font-weight: bold;
        }
        .profile-details h1 {
            margin: 0 0 5px 0;
            color: #4b5d6b;
            font-size: 1.5em;
        }
        .profile-details p {
            margin: 0;
            color: #787878;
            font-size: 0.95em;
        }
        .profile-stats {
            display: flex;
            gap: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 1.5em;
            font-weight: bold;
            color: #40A09C;
            display: block;
        }
        .stat-label {
            font-size: 0.9em;
            color: #787878;
            margin-top: 5px;
        }
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .btn-post {
            background: #40A09C;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 7px;
            font-size: 0.97em;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }
        .btn-post:hover { background: #278a84; }
        .alert {
            padding: 12px 16px;
            border-radius: 7px;
            margin-bottom: 20px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .cards-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-top: 12px;
        }
        .post-card {
            background: #fff;
            border-radius: 7px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
            padding: 19px 15px 11px 15px;
            min-width: 0;
        }
        .post-meta { font-size: 0.90em; color: #999; margin-bottom: 4px; }
        .post-title {
            font-weight: 600;
            font-size: 1.09em;
            margin-bottom: 4px;
            color: #4b5d6b;
        }
        .post-title a {
            text-decoration: none;
            color: inherit;
        }
        .post-title a:hover {
            color: #40A09C;
        }
        .post-desc {
            font-size: 0.98em;
            color: #314057;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .post-stats {
            display: flex;
            gap: 16px;
            font-size: 0.95em;
            color: #787878;
            margin-bottom: 7px;
        }
        .post-categories {
            display: flex;
            gap: 8px;
            margin-bottom: 3px;
        }
        .post-category-badge {
            padding: 3px 14px;
            background: #40A09C;
            color: #fff;
            border-radius: 5px;
            font-size: 0.91em;
            border: none;
        }
        .post-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 0.9em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            border: none;
        }
        .btn-edit {
            background: #40A09C;
            color: #fff;
        }
        .btn-edit:hover {
            background: #278a84;
        }
        .btn-delete {
            background: #dc3545;
            color: #fff;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: #40A09C;
            background: #fff;
            border: 1px solid #e0e0e0;
        }
        .pagination .active {
            background: #40A09C;
            color: #fff;
            border-color: #40A09C;
        }
        @media (max-width: 980px) {
            .main-container { flex-direction: column; }
            .dashboard-header { flex-direction: column; gap: 9px;}
            .profile-info { flex-direction: column; text-align: center; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>
<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-row">
            <img src="{{ asset('logo.png') }}" alt="Logo">
            <div>
                <div class="logo-title">CHATTER BOX</div>
                <div class="logo-sub">Express yourself everyday</div>
            </div>
        </div>
        <ul class="menu-list">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-comments"></i>Diskusi</a></li>
            <li><i class="fa fa-compass"></i>Jelajahi Topik-Topik</li>
            <li class="active"><a href="{{ route('profile') }}"><i class="fa fa-file-alt"></i>Postingan Saya</a></li>
            <li><i class="fa fa-heart"></i>Daftar Suka</li>
            <li><i class="fa fa-cog"></i>Pengaturan</li>
        </ul>
    </aside>
    <!-- Main Content -->
    <section class="dashboard-content">
        <div class="profile-header">
            <div class="profile-info">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="profile-details">
                    <h1>{{ $user->name }}</h1>
                    <p>{{ $user->email }}</p>
                </div>
            </div>
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-value">{{ $user->posts()->count() }}</span>
                    <span class="stat-label">Total Postingan</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $user->posts()->sum('views') }}</span>
                    <span class="stat-label">Total Views</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $user->posts()->sum('likes') }}</span>
                    <span class="stat-label">Total Likes</span>
                </div>
            </div>
        </div>

        <div class="dashboard-header">
            <h1 style="margin: 0; color: #4b5d6b;">Postingan Saya</h1>
            <a href="{{ route('posts.create') }}" class="btn-post">
                <i class="fa fa-plus"></i> Buat Postingan Baru
            </a>
        </div>

        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 12px 16px; border-radius: 7px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Post Cards List -->
        <div class="cards-list">
            @forelse($posts as $post)
                <div class="post-card">
                    <div class="post-meta">{{ $post->created_at->format('d M Y, H:i') }}</div>
                    <div class="post-title">
                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    </div>
                    <div class="post-desc">{{ $post->content }}</div>
                    <div class="post-stats">
                        <span><i class="fa fa-eye"></i> {{ $post->views }}</span>
                        <span><i class="fa fa-comment"></i> {{ $post->comments_count }}</span>
                        <span><i class="fa fa-thumbs-up"></i> {{ $post->likes }}</span>
                        <span><i class="fa fa-thumbs-down"></i> {{ $post->dislikes }}</span>
                    </div>
                    @if($post->category)
                        <div class="post-categories">
                            <span class="post-category-badge">{{ $post->category }}</span>
                        </div>
                    @endif
                    <div class="post-actions">
                        <a href="{{ route('posts.edit', $post) }}" class="btn-edit">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="post-card">
                    <p style="text-align: center; color: #999; padding: 20px;">
                        Belum ada postingan. <a href="{{ route('posts.create') }}" style="color: #40A09C;">Buat postingan pertama Anda!</a>
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="pagination">
                {{ $posts->links() }}
            </div>
        @endif
    </section>
</div>
</body>
</html>



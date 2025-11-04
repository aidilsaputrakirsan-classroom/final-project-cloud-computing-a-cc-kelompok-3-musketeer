<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Chatter Box</title>
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
        .menu-list li i {
            margin-right: 10px;
            font-size: 1.07em;
        }
        .faq-box {
            margin: 32px 10px 12px 10px;
            padding: 11px 12px;
            background: #e3f6f8;
            border-radius: 7px;
            text-align: center;
        }
        .faq-btn {
            background: #40A09C;
            border: none;
            color: #fff;
            padding: 5px 16px;
            border-radius: 5px;
            margin-top: 7px;
            font-size: 0.97em;
            cursor: pointer;
        }
        .dashboard-content {
            flex: 1;
            padding: 16px 22px 24px 22px;
            min-width: 0;
        }
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .search-bar {
            width: 270px;
            padding: 7px 10px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 0.99em;
            background: #fff;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #eee;
            background-size: cover;
        }
        .notif-icon {
            font-size: 1.1em;
            color: #aaa;
            cursor: pointer;
        }
        .content-list-controls {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 8px;
            margin-top: 5px;
        }
        .btn-filter, .btn-category {
            background: #e7f6fa;
            color: #40a09c;
            border: none;
            padding: 6px 17px;
            border-radius: 16px;
            font-size: 0.97em;
            cursor: pointer;
        }
        .btn-post {
            background: #40A09C;
            color: #fff;
            border: none;
            padding: 7px 17px;
            border-radius: 7px;
            font-size: 0.97em;
            font-weight: 500;
            margin-left: auto;
            transition: background 0.2s;
        }
        .btn-post:hover { background: #278a84; }
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
        .post-author-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }
        .author-avatar {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: #ddd;
            background-size: cover;
        }
        .author-name {
            font-weight: 600;
            color: #d49d3d;
            font-size: 0.95em;
        }
        .post-meta { font-size: 0.90em; color: #999; margin-bottom: 4px; }
        .post-title {
            font-weight: 600;
            font-size: 1.09em;
            margin-bottom: 4px;
            color: #4b5d6b;
        }
        .post-desc {
            font-size: 0.98em;
            color: #314057;
            margin-bottom: 4px;
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
        @media (max-width: 980px) {
            .main-container { flex-direction: column; }
            .dashboard-header { flex-direction: column; gap: 9px;}
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
            <li class="active"><i class="fa fa-comments"></i>Diskusi</li>
            <li><i class="fa fa-compass"></i>Jelajahi Topik-Topik</li>
            <li><i class="fa fa-file-alt"></i>Postingan Saya</li>
            <li><i class="fa fa-heart"></i>Daftar Suka</li>
            <li><i class="fa fa-cog"></i>Pengaturan</li>
        </ul>
        <div class="faq-box">
            <div>Ada Kendala?</div>
            <button class="faq-btn">Lihat Bantuan & FAQ</button>
        </div>
    </aside>
    <!-- Main Content -->
    <section class="dashboard-content">
        <div class="dashboard-header">
            <input type="text" class="search-bar" placeholder="Cari postingan ngtren saat ini?">
            <div class="user-info">
                <i class="fa fa-bell notif-icon"></i>
                <div class="user-avatar" style="background-image:url('{{ asset('avatar.jpg') }}');"></div>
            </div>
        </div>
        <div class="content-list-controls">
            <button class="btn-filter">Baru</button>
            <button class="btn-category"><i class="fa fa-filter"></i> Kategori</button>
            <button class="btn-post">+ Buat Postingan</button>
        </div>
        <!-- Post Cards List -->
        <div class="cards-list">
            <div class="post-card">
                <div class="post-author-row">
                    <div class="author-avatar" style="background-image:url('{{ asset('author1.jpg') }}');"></div>
                    <div class="author-name">Sci-Fi Enthusiast</div>
                    <span style="font-size:0.86em;color:#f7b448;font-weight:500;margin-left:10px;">Sci-Fi Enthusiast</span>
                </div>
                <div class="post-title">Which of sci-fi's favourite technologies would you like to see become a reality?</div>
                <div class="post-meta">09:00 pm</div>
                <div class="post-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Augue magna justo, volutpat, non amet massa viverra euismod id.</div>
                <div class="post-stats">
                    <span><i class="fa fa-eye"></i>60</span>
                    <span><i class="fa fa-comment"></i>3</span>
                    <span><i class="fa fa-thumbs-up"></i>3</span>
                    <span><i class="fa fa-thumbs-down"></i>3</span>
                </div>
                <div class="post-categories">
                    <span class="post-category-badge">Sci-fi</span>
                    <span class="post-category-badge">Sci-fi</span>
                </div>
            </div>
            <div class="post-card">
                <div class="post-author-row">
                    <div class="author-avatar" style="background-image:url('{{ asset('author2.jpg') }}');"></div>
                    <div class="author-name">User Lain</div>
                    <span style="font-size:0.86em;color:#f7b448;font-weight:500;margin-left:10px;">User Lain</span>
                </div>
                <div class="post-title">Something just happened</div>
                <div class="post-meta">09:00 pm</div>
                <div class="post-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Augue magna justo, volutpat, non amet massa viverra euismod id.</div>
                <div class="post-stats">
                    <span><i class="fa fa-eye"></i>60</span>
                    <span><i class="fa fa-comment"></i>3</span>
                    <span><i class="fa fa-thumbs-up"></i>3</span>
                    <span><i class="fa fa-thumbs-down"></i>3</span>
                </div>
                <div class="post-categories">
                    <span class="post-category-badge">Sci-fi</span>
                    <span class="post-category-badge">Sci-fi</span>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>

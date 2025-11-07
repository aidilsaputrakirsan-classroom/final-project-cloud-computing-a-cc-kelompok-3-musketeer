<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Chatter Box')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
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

        /* ==== SIDEBAR ==== */
        .sidebar {
            background: #fff;
            width: 230px;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed; /* tetap di tempat */
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
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
            transition: all 0.2s ease;
        }
        .menu-list li.active,
        .menu-list li:hover {
            background: #e7f6fa;
            border-radius: 6px;
            color: #40A09C;
        }
        .menu-list li i {
            margin-right: 10px;
        }
        .faq-box {
            margin: 32px 10px 12px 10px;
            padding: 18px 12px;
            background: #e3f6f8;
            border-radius: 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .faq-image {
            width: 120px;
            margin-bottom: 10px;
        }

        .faq-text {
            font-size: 1em;
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
        }

        .faq-btn {
            background: #40A09C;
            border: none;
            color: #fff;
            padding: 8px 18px;
            border-radius: 7px;
            font-size: 0.95em;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .faq-btn:hover {
            background: #2e8985;
        }


        /* ==== KONTEN UTAMA ==== */
        .dashboard-content {
            flex: 1;
            padding: 16px 22px 24px 22px;
            background: #f5f6fa;
            margin-left: 230px; /* offset sidebar */
            min-width: 0;
            height: 100vh;
            overflow-y: auto; /* hanya konten yang scroll */
        }

        /* ==== KOMPONEN TAMBAHAN ==== */
        .post-card {
            background: #fff;
            border-radius: 7px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
            padding: 19px 15px;
            margin-bottom: 20px;
        }
        .post-card h4 {
            color: #4b5d6b;
            font-weight: 600;
        }
        .post-card p {
            color: #314057;
        }
    </style>
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
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}" 
                onclick="window.location='{{ url('/dashboard') }}'">
                <i class="fa fa-comments"></i> Diskusi
            </li>
            <li><i class="fa fa-compass"></i> Jelajahi Topik</li>
            <li><i class="fa fa-file-alt"></i> Postingan Saya</li>
            <li><i class="fa fa-heart"></i> Daftar Suka</li>
            <li><i class="fa fa-cog"></i> Pengaturan</li>
        </ul>

        <div class="faq-box">
            <div class="faq-text">Ada Kendala?</div>
            <a href="{{ route('faq.index') }}" class="faq-btn">Lihat Bantuan & FAQ</a>
        </div>
        </aside>

    <!-- Konten Utama -->
    <section class="dashboard-content">
        @yield('content')
    </section>
</div>
</body>
</html>

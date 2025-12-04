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
            position: fixed;
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

        /* ==== KONTEN UTAMA ==== */
        .dashboard-content {
            flex: 1;
            padding: 16px 22px 24px 22px;
            background: #f5f6fa;
            margin-left: 230px;
            min-width: 0;
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<div class="main-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-row">
            <img src="{{ asset('images/screenshots/logochatterbox.png') }}" alt="Logo">
            <div>
                <div class="logo-title">CHATTER BOX</div>
                <div class="logo-sub">Express yourself everyday</div>
            </div>
        </div>

        <ul class="menu-list">

            {{-- Menu Laporan Postingan --}}
            <li class="{{ request()->is('admin/reports') ? 'active' : '' }}"
                onclick="window.location='{{ route('admin.reports.index') }}'">
                <i class="fa fa-circle-exclamation"></i> Laporan Postingan
            </li>

            {{-- Menu History Laporan Postingan --}}
            <li class="{{ request()->is('admin/reports/history') || request()->is('admin/reports/history*') ? 'active' : '' }}"
                onclick="window.location='{{ route('admin.reports.history') }}'">
                <i class="fa fa-history"></i> History Laporan
            </li>
            
            {{-- Logout --}}
            <li onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="margin-top:20px;color:#d9534f;">
                <i class="fa fa-right-from-bracket"></i> Logout
            </li>

        </ul>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>

    </aside>

    <!-- Konten Utama -->
    <section class="dashboard-content">
        @yield('content')
    </section>

</div>
</body>
</html>

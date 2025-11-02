<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Chatterbox</title>
    <style>
        body {
            background: #ecf0f1;
            font-family: Arial, sans-serif;
        }
        .dash-container {
            max-width: 700px;
            margin: 60px auto;
            padding: 40px 36px;
            background: #fff;
            border-radius: 9px;
            box-shadow: 0 6px 24px rgba(44,62,80,0.11);
            text-align: center;
        }
        .dash-container h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .dash-container p {
            color: #555;
            margin-bottom: 24px;
        }
        .logout-btn {
            background: #e03e4e;
            color: #fff;
            padding: 10px 24px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .feature-list {
            list-style: none;
            padding-left: 0;
        }
        .feature-list li {
            background: #d6eaf8;
            color: #2874a6;
            margin-bottom: 8px;
            padding: 10px 0;
            border-radius: 5px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div class="dash-container">
    <h2>Selamat Datang di Dashboard Chatterbox!</h2>
    <p>Halo, <strong>{{ Auth::user()->name }}</strong>! Berikut fitur yang bisa kamu gunakan:</p>
    <ul class="feature-list">
        <li>CRUD Postingan</li>
        <li>Komentar & Like/Dislike</li>
        <li>Kategori Postingan (#Alam, #Lucu, dll)</li>
        <li>Tema Gelap & Terang</li>
        <li>Report, Notifikasi & Bantuan/FAQ</li>
    </ul>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>
</body>
</html>

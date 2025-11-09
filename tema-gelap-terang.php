<!DOCTYSPE html>
<html lang="id" class="transition-all duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan | Chatter Box</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #00A884;
            --bg-light: #FFFFFF;
            --bg-dark: #1E1E1E;
            --text-light: #000000;
            --text-dark: #FFFFFF;
            --card-bg-light: #F7F7F7;
            --card-bg-dark: #2A2A2A;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
            color: var(--text-light);
            background-color: var(--bg-light);
            transition: background-color 0.3s, color 0.3s;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background-color: #F9FAFB;
            border-right: 1px solid #E5E7EB;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo img {
            width: 35px;
            margin-right: 10px;
        }

        .logo h1 {
            color: var(--primary-color);
            font-size: 18px;
            margin: 0;
        }

        .menu {
            list-style: none;
            padding: 0;
        }

        .menu li {
            margin: 18px 0;
            display: flex;
            align-items: center;
            color: #333;
            cursor: pointer;
        }

        .menu li i {
            margin-right: 10px;
        }

        .menu li.active {
            font-weight: 600;
        }

        .help-box {
            background: #E9F9F4;
            padding: 16px;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
        }

        .help-box button {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
        }

        /* Content */
        .content {
            flex: 1;
            padding: 30px 50px;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .content h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        /* Toggle Switch */
        .toggle-container {
            display: flex;
            align-items: center;
            background: #F2F2F2;
            padding: 10px 15px;
            border-radius: 8px;
            width: fit-content;
        }

        .toggle-label {
            margin-right: 10px;
            font-weight: 500;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0;
            right: 0; bottom: 0;
            background-color: #ccc;
            border-radius: 34px;
            transition: 0.4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px; width: 18px;
            left: 3px; bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.4s;
        }

        input:checked + .slider {
            background-color: var(--primary-color);
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }

        body.dark-mode .sidebar {
            background-color: #111111;
            border-color: #2D2D2D;
            color: var(--text-dark);
        }

        body.dark-mode .menu li {
            color: #DDD;
        }

        body.dark-mode .help-box {
            background: #222;
            color: #FFF;
        }

        body.dark-mode .content {
            background-color: var(--bg-dark);
        }

        body.dark-mode .card {
            background: var(--card-bg-dark);
            color: #FFF;
            border-color: #3A3A3A;
        }

        /* Card post */
        .card {
            background: var(--card-bg-light);
            padding: 16px;
            border-radius: 10px;
            border: 1px solid #E5E5E5;
            max-width: 600px;
            transition: 0.3s;
        }

        .card h4 {
            font-size: 15px;
            margin: 0 0 5px;
        }

        .card p {
            font-size: 14px;
            color: #666;
        }

        .tag {
            display: inline-block;
            background-color: var(--primary-color);
            color: #fff;
            border-radius: 4px;
            padding: 3px 8px;
            margin-top: 8px;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 10px 20px;
                border-right: none;
                border-bottom: 1px solid #E5E7EB;
            }

            .menu {
                display: none;
            }

            .help-box, .sidebar p {
                display: none;
            }

            .content {
                padding: 20px;
            }

            .content h1 {
                font-size: 22px;
            }

            .toggle-container {
                width: 100%;
                justify-content: space-between;
                max-width: 350px;
            }

            .card {
                width: 100%;
                margin-top: 20px;
            }
        }

        @media (max-width: 480px) {
            .toggle-label {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/1384/1384031.png" alt="logo">
            <h1>CHATTER BOX</h1>
        </div>

        <ul class="menu">
            <li class="active"><i>💬</i> Diskusi</li>
            <li><i>🌐</i> Jelajahi Topik-Topik</li>
            <li><i>📄</i> Postingan Saya</li>
            <li><i>❤️</i> Daftar Suka</li>
        </ul>

        <div>
            <div class="help-box">
                <p><strong>Ada Kendala?</strong></p>
                <button>Lihat Bantuan & FAQ</button>
            </div>
            <p style="text-align:center; margin-top:15px;">⚙️ Pengaturan</p>
        </div>
    </div>

    <div class="content">
        <h1>Pengaturan</h1>

        <div class="toggle-container">
            <span class="toggle-label">Mode Terang</span>
            <label class="switch">
                <input type="checkbox" id="darkModeToggle">
                <span class="slider"></span>
            </label>
        </div>

        <h2 style="margin-top:40px;">Konten Tampilan</h2>
        <div class="card">
            <h4>Which of sci-fi’s favourite technologies would you like to see become a reality?</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Augue magna justo, volutpat, nec amet massa viverra euismod id.</p>
            <div class="tag">Sci-Fi</div>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('darkModeToggle');
        const body = document.body;

        // Cek preferensi tema yang tersimpan
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            toggle.checked = true;
        }

        // Ubah tema
        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
</body>
</html>

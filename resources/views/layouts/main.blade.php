<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    {{-- CSRF WAJIB UNTUK AJAX LIKE/DISLIKE --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Chatter Box')</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

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
            transition: 0.2s ease;
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
            margin: 32px 10px 12px;
            padding: 18px 12px;
            background: #e3f6f8;
            border-radius: 10px;
            text-align: center;
        }
        .faq-text {
            font-size: 1em;
            margin-bottom: 6px;
        }
        .faq-btn {
            background: #40A09C;
            border: none;
            color: #fff;
            padding: 8px 18px;
            border-radius: 7px;
            font-size: 0.95em;
            text-decoration: none;
        }

        /* ==== KONTEN UTAMA ==== */
        .dashboard-content {
            flex: 1;
            padding: 16px 22px 24px;
            background: #f5f6fa;
            margin-left: 230px;
            height: 100vh;
            overflow-y: auto;
        }

        /* ==== REACTION BUTTON ==== */
        .reaction-btn {
            border-radius: 6px;
            padding: 6px 10px;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .12s ease;
        }
        .reaction-btn.like-btn.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.06);
            border-color: rgba(13, 110, 253, 0.12);
        }
        .reaction-btn.dislike-btn.active {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.06);
            border-color: rgba(220, 53, 69, 0.12);
        }
        .reaction-btn.loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>

<body>
<div class="main-container">
    {{-- SIDEBAR --}}
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

            <li class="{{ request()->is('topics*') ? 'active' : '' }}"
                onclick="window.location='{{ route('categories.index') }}'">
                <i class="fa fa-compass"></i> Jelajahi Topik
            </li>

            <li class="{{ request()->is('my-posts*') ? 'active' : '' }}"
                onclick="window.location='{{ url('/my-posts') }}'">
                <i class="fa fa-file-alt"></i> Postingan Saya
            </li>

            <li class="{{ request()->is('daftar-suka') || request()->is('my-reactions') ? 'active' : '' }}"
                onclick="window.location='{{ route('user.reactions.index') }}'">
                <i class="fa fa-heart"></i> Daftar Suka
            </li>

            <li onclick="window.location='#'">
                <i class="fa fa-cog"></i> Pengaturan
            </li>
        </ul>

        <div class="faq-box">
            <div class="faq-text">Ada Kendala?</div>
            <a href="{{ route('faq.index') }}" class="faq-btn">Lihat Bantuan & FAQ</a>
        </div>
    </aside>

    {{-- KONTEN UTAMA --}}
    <section class="dashboard-content">
        @yield('content')
    </section>
</div>

{{-- ========================= --}}
{{-- JS GLOBAL LIKE/DISLIKE   --}}
{{-- ========================= --}}
<script>
(function () {
    const meta = document.querySelector('meta[name="csrf-token"]');
    const csrf = meta ? meta.content : null;

    function setButtonsLoading(likeBtn, dislikeBtn, loading) {
        [likeBtn, dislikeBtn].forEach(btn => {
            if (!btn) return;
            if (loading) {
                btn.classList.add('loading');
                btn.disabled = true;
            } else {
                btn.classList.remove('loading');
                btn.disabled = false;
            }
        });
    }

    function findCountElements(wrapper, postId) {
        let likesEl = null;
        let dislikesEl = null;

        if (wrapper) {
            likesEl = wrapper.querySelector('.likes-count');
            dislikesEl = wrapper.querySelector('.dislikes-count');
        }

        if (!likesEl) {
            likesEl = document.querySelector('.likes-count[data-post-id="' + postId + '"]') || null;
        }
        if (!dislikesEl) {
            dislikesEl = document.querySelector('.dislikes-count[data-post-id="' + postId + '"]') || null;
        }

        return {likesEl, dislikesEl};
    }

    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.reaction-btn');
        if (!btn) return;

        if (btn.classList.contains('loading')) return;

        const postId = btn.dataset.postId;
        if (!postId) return;

        const isLike = btn.classList.contains('like-btn');
        const currentlyActive = btn.classList.contains('active');
        const type = currentlyActive ? 'remove' : (isLike ? 'like' : 'dislike');

        // Ambil SEMUA tombol reaksi untuk post ini (di dashboard, detail, my-posts)
        const allButtons = document.querySelectorAll('.reaction-btn[data-post-id="' + postId + '"]');
        let likeBtn = null;
        let dislikeBtn = null;
        allButtons.forEach(b => {
            if (b.classList.contains('like-btn')) likeBtn = b;
            if (b.classList.contains('dislike-btn')) dislikeBtn = b;
        });

        // Wrapper buat cari counter
        let wrapper = btn.closest('[data-post-id]');
        if (!wrapper) {
            wrapper = document.querySelector('[data-post-id="' + postId + '"]');
        }

        const {likesEl, dislikesEl} = findCountElements(wrapper, postId);

        setButtonsLoading(likeBtn, dislikeBtn, true);

        if (!csrf) {
            alert('CSRF token tidak ditemukan. Pastikan meta tag csrf ada di layout.');
            setButtonsLoading(likeBtn, dislikeBtn, false);
            return;
        }

        try {
            const res = await fetch('/posts/' + postId + '/react', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({type})
            });

            if (!res.ok) {
                if (res.status === 419) {
                    alert('Session/CSRF token kadaluarsa. Reload halaman dan coba lagi.');
                } else if (res.status === 401 || res.status === 302) {
                    alert('Silakan login untuk memberi reaksi.');
                } else {
                    alert('Terjadi kesalahan saat mengirim reaksi. Lihat console.');
                }
                console.error('React failed', res.status, await res.text());
                return;
            }

            const contentType = res.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                console.error('Response bukan JSON', await res.text());
                alert('Respon server tidak valid. Cek console/network.');
                return;
            }

            const data = await res.json();

            // Update angka
            if (likesEl && typeof data.likes !== 'undefined') {
                likesEl.textContent = data.likes;
            }
            if (dislikesEl && typeof data.dislikes !== 'undefined') {
                dislikesEl.textContent = data.dislikes;
            }

            // Hapus dulu active dari dua-duanya UNTUK POST INI
            [likeBtn, dislikeBtn].forEach(b => b && b.classList.remove('active'));

            // Tambahkan kembali sesuai user_reaction
            if (data.user_reaction === 1 && likeBtn) {
                likeBtn.classList.add('active');
            } else if (data.user_reaction === -1 && dislikeBtn) {
                dislikeBtn.classList.add('active');
            }
            // Kalau 0 => dua-duanya tetap non-active (berarti user batal reaksi)

        } catch (err) {
            console.error('React error', err);
            alert('Gagal mengirim reaksi. Periksa koneksi Anda.');
        } finally {
            setButtonsLoading(likeBtn, dislikeBtn, false);
        }
    });
})();
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan & FAQ - Chatter Box</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --chatter-blue: #4C9CBD;
        }
        .text-chatter { color: var(--chatter-blue) !important; }
        .btn-chatter {
            background-color: var(--chatter-blue);
            border: none;
            color: white;
        }
        .btn-chatter:hover {
            background-color: #3b88a8;
        }
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <h2 class="fw-bold mb-4 text-chatter">Bantuan & FAQ</h2>

        {{-- ================== KATEGORI 1 ================== --}}
        <h4 class="fw-semibold text-chatter mb-3"><i class="bi bi-person-circle me-2"></i>Akun & Profil</h4>
        <div class="row g-3 mb-5">
            @foreach ($faqData['akun'] as $slug => $faq)
                <div class="col-md-6">
                    <a href="{{ route('faq.show', ['slug' => $slug]) }}" 
                       class="card p-3 text-decoration-none text-dark shadow-sm h-100">
                        <div class="d-flex align-items-center">
                            <i class="bi {{ $faq['icon'] }} fs-3 text-chatter me-3"></i>
                            <span class="fw-semibold">{{ $faq['title'] }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- ================== KATEGORI 2 ================== --}}
        <h4 class="fw-semibold text-chatter mb-3"><i class="bi bi-chat-left-text-fill me-2"></i>Postingan & Komentar</h4>
        <div class="row g-3 mb-5">
            @foreach ($faqData['postingan'] as $slug => $faq)
                <div class="col-md-6">
                    <a href="{{ route('faq.show', ['slug' => $slug]) }}" 
                       class="card p-3 text-decoration-none text-dark shadow-sm h-100">
                        <div class="d-flex align-items-center">
                            <i class="bi {{ $faq['icon'] }} fs-3 text-chatter me-3"></i>
                            <span class="fw-semibold">{{ $faq['title'] }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- ================== KATEGORI 3 ================== --}}
        <h4 class="fw-semibold text-chatter mb-3"><i class="bi bi-shield-lock-fill me-2"></i>Pengaturan & Keamanan</h4>
        <div class="row g-3 mb-5">
            @foreach ($faqData['keamanan'] as $slug => $faq)
                <div class="col-md-6">
                    <a href="{{ route('faq.show', ['slug' => $slug]) }}" 
                       class="card p-3 text-decoration-none text-dark shadow-sm h-100">
                        <div class="d-flex align-items-center">
                            <i class="bi {{ $faq['icon'] }} fs-3 text-chatter me-3"></i>
                            <span class="fw-semibold">{{ $faq['title'] }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- ================== KATEGORI 4 ================== --}}
        <h4 class="fw-semibold text-chatter mb-3"><i class="bi bi-bug-fill me-2"></i>Kendala Teknis</h4>
        <div class="row g-3 mb-5">
            @foreach ($faqData['kendala'] as $slug => $faq)
                <div class="col-md-6">
                    <a href="{{ route('faq.show', ['slug' => $slug]) }}" 
                       class="card p-3 text-decoration-none text-dark shadow-sm h-100">
                        <div class="d-flex align-items-center">
                            <i class="bi {{ $faq['icon'] }} fs-3 text-chatter me-3"></i>
                            <span class="fw-semibold">{{ $faq['title'] }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- ================== KATEGORI 5 ================== --}}
        <h4 class="fw-semibold text-chatter mb-3"><i class="bi bi-telephone-fill me-2"></i>Bantuan & Kontak</h4>
        <div class="row g-3 mb-5">
            @foreach ($faqData['kontak'] as $slug => $faq)
                <div class="col-md-6">
                    <a href="{{ route('faq.show', ['slug' => $slug]) }}" 
                       class="card p-3 text-decoration-none text-dark shadow-sm h-100">
                        <div class="d-flex align-items-center">
                            <i class="bi {{ $faq['icon'] }} fs-3 text-chatter me-3"></i>
                            <span class="fw-semibold">{{ $faq['title'] }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- TOMBOL HUBUNGI --}}
        <div class="text-center mt-5">
            <p class="mb-2 text-muted">Tidak menemukan jawaban?</p>
            <a href="https://wa.me/6281234567890" class="btn btn-chatter px-4 py-2">
                <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
            </a>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Bantuan & FAQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .btn-chatter {
            background-color: #4A9ABF;
            border: none;
        }
        .btn-chatter:hover {
            background-color: #3a86a3;
        }
        .icon-chatter {
            color: #4A9ABF;
        }
        .fw-bold {
            color: #333;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <a href="{{ route('faq.index') }}" class="text-decoration-none d-inline-flex align-items-center mb-4">
            <i class="bi bi-arrow-left-circle-fill fs-4 icon-chatter me-2"></i>
            <span>Kembali ke FAQ</span>
        </a>

        <div class="card shadow-sm p-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bi {{ $icon }} fs-2 icon-chatter me-2"></i>
                <h3 class="fw-bold mb-0">{{ $title }}</h3>
            </div>

            <ol class="list-group list-group-numbered">
                @foreach ($steps as $step)
                    <li class="list-group-item">{{ $step }}</li>
                @endforeach
            </ol>
        </div>

        <div class="text-center mt-4">
            <p class="mb-2">Masih butuh bantuan?</p>
            <a href="https://wa.me/6281234567890" class="btn btn-chatter text-white px-4">
                <i class="bi bi-whatsapp me-2"></i> Hubungi Kami
            </a>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Postingan | Chatter Box</title>
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 7px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        h1 {
            color: #4b5d6b;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #4b5d6b;
            font-weight: 600;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 0.97em;
            font-family: inherit;
            box-sizing: border-box;
        }
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        .error {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
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
        .alert-error {
            padding: 12px 16px;
            border-radius: 7px;
            margin-bottom: 20px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Buat Postingan Baru</h1>

    @if(session('error'))
        <div style="padding: 12px 16px; border-radius: 7px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Judul Postingan *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Konten *</label>
            <textarea id="content" name="content" required>{{ old('content') }}</textarea>
            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="category">Kategori</label>
            <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="Contoh: Teknologi, Olahraga, dll">
            @error('category')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Buat Postingan</button>
            <a href="{{ route('my-posts') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
</body>
</html>


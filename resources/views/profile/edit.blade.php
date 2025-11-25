<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Chatter Box</title>
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
        .profile-picture-section {
            margin-bottom: 30px;
            text-align: center;
        }
        .profile-picture-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: #40A09C;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 4em;
            font-weight: bold;
            background-size: cover;
            background-position: center;
            border: 4px solid #e0e0e0;
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
        input[type="text"], input[type="email"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 0.97em;
            font-family: inherit;
            box-sizing: border-box;
        }
        input[type="file"] {
            padding: 8px;
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
        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .file-input-label {
            display: block;
            padding: 10px;
            background: #f8f9fa;
            border: 1px dashed #ddd;
            border-radius: 7px;
            text-align: center;
            cursor: pointer;
            color: #787878;
        }
        .file-input-label:hover {
            background: #e9ecef;
        }
        input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>
<div class="container">
    <h1>Edit Profile</h1>

    @if(session('success'))
        <div style="padding: 12px 16px; border-radius: 7px; margin-bottom: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 12px 16px; border-radius: 7px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="profile-picture-section">
            <div class="profile-picture-preview" id="preview" style="@if($user->profile_picture) background-image: url('{{ Storage::url($user->profile_picture) }}'); @endif">
                @if(!$user->profile_picture)
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div class="form-group">
                <div class="file-input-wrapper">
                    <label for="profile_picture" class="file-input-label">
                        <i class="fa fa-camera"></i> Pilih Foto Profile
                    </label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(this)">
                </div>
                @error('profile_picture')
                    <div class="error">{{ $message }}</div>
                @enderror
                <small style="color: #787878; display: block; margin-top: 5px;">Format: JPG, PNG, GIF (Max: 2MB)</small>
            </div>
        </div>

        <div class="form-group">
            <label for="name">Nama *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('my-posts') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.backgroundImage = `url(${e.target.result})`;
                preview.innerHTML = '';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>




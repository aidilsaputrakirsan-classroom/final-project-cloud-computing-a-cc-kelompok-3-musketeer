<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | Chatterbox</title>
    <style>
        /* Gunakan style serupa login supaya konsisten */
        body {background: #f7f7f7; font-family: Arial, sans-serif;}
        .register-container {
            max-width: 420px; margin: 70px auto; padding: 35px 28px; background: #fff;
            border-radius: 9px; box-shadow: 0 6px 28px rgba(0,0,0,0.13);}
        .register-container h2 {margin-bottom: 18px; text-align: center; color: #34495e;}
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%; margin-bottom: 13px; padding: 8px 10px; border-radius: 5px; border: 1px solid #ccc;
        }
        button {width: 100%; background: #3498db; color: #fff; padding: 10px 0; border: none; border-radius: 5px; cursor: pointer;}
        .error-message {background: #ef9a9a; color: #c62828; padding: 6px 12px; border-radius: 5px; margin-bottom: 10px; text-align: center;}
    </style>
</head>
<body>
<div class="register-container">
    <h2>Registrasi Akun</h2>
    @if ($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    <form method="POST" action="/register">
        @csrf
        <input type="text" name="name" placeholder="Nama Lengkap" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password (min 6 karakter)" required>
        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
        <button type="submit">Daftar</button>
    </form>
    <div style="margin-top:10px;text-align:center;">
        <a href="/login">Sudah punya akun? Login</a>
    </div>
</div>
</body>
</html>

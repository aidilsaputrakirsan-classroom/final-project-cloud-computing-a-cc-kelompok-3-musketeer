<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Chatterbox</title>
    <style>
        body {
            background: #f7f7f7;
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 70px auto;
            padding: 35px 28px;
            background: #fff;
            border-radius: 9px;
            box-shadow: 0 6px 28px rgba(0,0,0,0.13);
        }
        .login-container h2 {
            margin-bottom: 24px;
            text-align: center;
            color: #34495e;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            margin-bottom: 15px;
            padding: 9px 11px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            background: #3498db;
            color: #fff;
            padding: 11px 0;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 6px;
        }
        .error-message {
            background: #ef9a9a;
            color: #c62828;
            padding: 6px 12px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Selamat Datang di Chatterbox</h2>
    @if(session()->has('failed'))
        <div class="error-message">{{ session('failed') }}</div>
    @endif
    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" placeholder="Alamat Email" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>

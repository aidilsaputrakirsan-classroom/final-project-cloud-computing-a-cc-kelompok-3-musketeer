<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Chatterbox</title>

    <style>
        body {
            background: #f0f3f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            max-width: 400px;
            margin: 70px auto;
            padding: 35px 28px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.10);
        }

        .login-container h2 {
            margin-bottom: 24px;
            text-align: center;
            color: #2f3e46;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 14px;
            padding: 11px 12px;
            font-size: 15px;
            border-radius: 7px;
            border: 1px solid #cfd8dc;
            background: #fafafa;
            transition: 0.2s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #40A09C;
            outline: none;
            background: #ffffff;
        }

        button {
            width: 100%;
            padding: 12px 0;
            background: #40A09C;
            color: #ffffff;
            font-size: 1rem;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 6px;
            transition: 0.2s;
        }

        button:hover {
            background: #2c7774;
        }

        .error-message {
            background: #fdeaea;
            color: #b32626;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 14px;
            font-size: 0.9rem;
            text-align: center;
            border: 1px solid #f5c2c7;
        }

        .register-link {
            text-align: center;
            margin-top: 16px;
            font-size: 0.95rem;
        }

        .register-link a {
            color: #40A09C;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

    </style>
</head>

<body>

<div class="login-container">

    <h2>Login ke Chatterbox</h2>

    @if(session()->has('failed'))
        <div class="error-message">{{ session('failed') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <input type="email" name="email" placeholder="Alamat Email" required autofocus>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <div class="register-link">
        <a href="/register">Belum punya akun? Register di sini</a>
    </div>

</div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | Chatter Box</title>
    <style>
        body {
<<<<<<< HEAD
            background: #f0f3f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .register-container {
            max-width: 400px;
            margin: 70px auto;
            padding: 35px 28px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.10);
        }

        .register-container h2 {
            margin-bottom: 24px;
            text-align: center;
            color: #2f3e46;
            font-weight: bold;
        }

        input[type="text"],
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

        input[type="text"]:focus,
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

        .login-link {
            text-align: center;
            margin-top: 16px;
            font-size: 0.95rem;
        }

        .login-link a {
            color: #40A09C;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
=======
            margin: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
        }

        .page-wrapper {
            max-width: 1200px;
            margin: 12px auto;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 60px;
            box-sizing: border-box;

            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .left-side {
            width: 52%;
        }

        .logo-row {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 90px;
            height: auto;
            margin-right: 18px;
        }

        .logo-text {
            font-size: 40px;
            font-weight: 700;
            color: #2e9c96;
            letter-spacing: 1px;
        }

        .logo-tagline {
            font-size: 13px;
            color: #555;
            margin-top: 4px;
        }

        .form-title {
            font-size: 32px;
            font-weight: 700;
            color: #5a2e2e;
            margin: 0 0 4px;
        }

        .form-subtitle {
            font-size: 15px;
            color: #555;
            margin-bottom: 26px;
        }

        .register-form input[type="text"],
        .register-form input[type="email"],
        .register-form input[type="password"] {
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 14px;
            padding: 12px 14px;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #d4d4d4;
            background: #f3f3f3;
            transition: .2s;
        }

        .register-form input:focus {
            border-color: #40A09C;
            outline: none;
            background: #ffffff;
        }

        .btn-primary {
            display: inline-block;
            width: 260px;
            padding: 12px 0;
            background: #40A09C;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: .2s;
        }

        .btn-primary:hover {
            background: #2c7774;
        }

        .error-message {
            background: #fdeaea;
            color: #b32626;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 14px;
            font-size: 0.9rem;
            border: 1px solid #f5c2c7;
        }

        .form-bottom {
            text-align: center;
            margin-top: 8px;
        }

        .login-link {
            margin-top: 18px;
            font-size: 14px;
            text-align: center;
        }

        .login-link a {
            color: #40A09C;
            text-decoration: none;
            font-weight: 600;
        }

        .right-side {
            width: 40%;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-start;
        }

        .illustration {
            width: 100%;
            max-width: 500px;
            margin-top: 40px;
        }

        .illustration img {
            width: 100%;
            height: auto;
            display: block;
        }

        @media (max-width: 992px) {
            .page-wrapper {
                max-width: 100%;
                margin: 0;
                border-radius: 0;
                padding: 20px;
                flex-direction: column;
                align-items: center;
            }
            .left-side,
            .right-side {
                width: 100%;
            }
            .right-side {
                align-items: center;
                margin-top: 30px;
            }
        }

            .password-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 40%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 20px;   
    color: #666;
}

>>>>>>> b78a563 (style: memperbaiki tampilan login dan register)
    </style>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<<<<<<< HEAD
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
        <input type="email" name="email" placeholder="Alamat Email" required>
        <input type="password" name="password" placeholder="Password (min 6 karakter)" required>
        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
        <button type="submit">Daftar</button>
    </form>

    <div class="login-link">
        <a href="/login">Sudah punya akun? Login</a>
    </div>
=======

<div class="page-wrapper">
    <div class="left-side">
        <div class="logo-row">
            <img src="{{ asset('logochatterbox.png') }}" alt="Chatter Box" class="logo-icon">
            <div>
                <div class="logo-text">CHATTER BOX</div>
                <div class="logo-tagline">Express yourself everyday</div>
            </div>
        </div>

        <div class="form-title">REGISTER</div>
        <div class="form-subtitle">Silahkan isi data untuk membuat akun baru</div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

    <form method="POST" action="{{ url('/register') }}" class="register-form">
        @csrf
        <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
        <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required>

        <div class="password-wrapper">
            <input id="password" type="password" name="password"
                placeholder="Password (min 6 karakter)" required>
            <i class="password-toggle fa fa-eye"
            onclick="togglePassword('password', this)"></i>
        </div>

        <div class="password-wrapper">
            <input id="password_confirmation" type="password" name="password_confirmation"
                placeholder="Konfirmasi Password" required>
            <i class="password-toggle fa fa-eye"
            onclick="togglePassword('password_confirmation', this)"></i>
        </div>


        <div class="form-bottom">
            <button type="submit" class="btn-primary">Daftar</button>
            <div class="login-link">
                Sudah punya akun?
                <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </form>

    </div>
    <div class="right-side">
        <div class="illustration">
            <img src="{{ asset('bg.png') }}" alt="People Network Illustration">
        </div>
    </div>
>>>>>>> b78a563 (style: memperbaiki tampilan login dan register)

</div>
<script>
function togglePassword(inputId, el) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    // toggle fa-eye <-> fa-eye-slash
    if (isPassword) {
        el.classList.remove('fa-eye');
        el.classList.add('fa-eye-slash');
    } else {
        el.classList.remove('fa-eye-slash');
        el.classList.add('fa-eye');
    }
}
</script>
</body>
</html>

@extends('layouts.app')

@section('content')
<style>
    /* Disable scroll hanya di halaman login */
    html, body {
        height: 100%;
        overflow: hidden;   /* 🚫 Scroll mati total */
    }

    body {
        background: #f5f7fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-wrapper {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid #e6e6e6;
        animation: fadein .6s ease;
    }

    @keyframes fadein {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .login-title {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        color: #222;
        margin-bottom: 5px;
    }

    .login-sub {
        text-align: center;
        font-size: 12px;
        color: #666;
        margin-bottom: 25px;
    }

    input {
        width: 100%;
        padding: 10px 12px;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #cdd3dd;
        outline: none;
        transition: .2s;
    }

    input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.25);
    }

    label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
        color: #333;
        margin-top: 12px;
    }

    .btn-login {
        width: 100%;
        background: #111827;
        color: white;
        border: none;
        padding: 10px;
        margin-top: 15px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: .25s;
    }

    .btn-login:hover {
        background: #000;
        transform: scale(1.02);
    }

    .error-box {
        background: #ffe5e5;
        color: #c70000;
        padding: 8px 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        border: 1px solid #ffb9b9;
        font-size: 13px;
    }

    .login-footer {
        text-align: center;
        margin-top: 10px;
        font-size: 12px;
        color: #666;
    }
</style>


<div class="login-wrapper">

    <div class="login-card">

        <div class="login-title">Masuk Akun</div>
        <p class="login-sub">Akses sistem fasilitas sekolah</p>

        @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                • {{ $error }}<br>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button class="btn-login">Masuk</button>
        </form>

        <p class="login-footer">Belum punya akun? Hubungi admin sekolah.</p>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilitas Sekolah</title>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* Tidak bisa scroll */
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(145deg, #eef2f7, #dfe6ee);
        }

        .container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            animation: fadein .8s ease;
            text-align: center;
        }

        @keyframes fadein {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            background: #111827;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 8px;
            color: #1f2937;
        }

        p {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 28px;
        }

        .btn {
            background: #111827;
            color: white;
            padding: 12px 26px;
            font-size: 15px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: .25s;
            font-weight: 600;
        }

        .btn:hover {
            background: black;
            transform: scale(1.05);
        }

        .footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #444;
        }
    </style>

</head>
<body>

    <div class="container">

        <div class="logo">FS</div>

        <h1>Fasilitas Sekolah</h1>
        <p>Aplikasi peminjaman ruang dan fasilitas sekolah</p>

        <button class="btn" onclick="window.location.href='{{ route('login') }}'">
            Masuk Sistem
        </button>

    </div>

    <div class="footer">
        © {{ date('Y') }} Fasilitas Sekolah — Semua hak dilindungi.
    </div>

</body>
</html>

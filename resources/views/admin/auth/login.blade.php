<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - E-Voting OSIS SMK Pertiwi Kuningan</title>
    
    <!-- Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome CDN untuk Ikon Presisi Figma -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #F3F8FC;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        /* Penyesuaian Logo tanpa potong lingkaran */
        .logo {
            width: 100px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Penyesuaian Teks Judul & Subtitle */
        h1 {
            color: #025088;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        p.subtitle {
            color: #38BDF8;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        p.tagline {
            color: #94A3B8;
            font-style: italic;
            font-size: 12px;
            letter-spacing: 0.3px;
            margin-bottom: 24px;
            text-transform: uppercase;
        }

        /* Card Form */
        .login-card {
            background-color: #FFFFFF;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            padding: 32px 28px;
            text-align: left;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
            transform: translateY(-3px);
        }

        .login-card h2 {
            text-align: center;
            color: #1E293B;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 22px;
        }

        label {
            font-size: 11px;
            font-weight: 700;
            color: #64748B;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background-color: #F8FAFC;
            font-size: 13px;
            color: #334155;
            outline: none;
            transition: all 0.2s ease;
        }

        .input-wrapper input::placeholder {
            color: #94A3B8;
        }

        .input-wrapper input:focus {
            border-color: #38BDF8;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        /* Style Ikon Input */
        .input-wrapper .icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 14px;
            pointer-events: none;
        }

        .input-wrapper .icon-right {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 14px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        /* Tombol Masuk Gradient */
        button.btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #00B4DB, #0083B0);
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 180, 219, 0.25);
            transition: opacity 0.2s, transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 4px;
        }

        button.btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 180, 219, 0.35);
        }

        button.btn-submit:active {
            transform: translateY(0) scale(0.97);
            box-shadow: 0 3px 8px rgba(0, 180, 219, 0.3);
        }

        /* Info Box */
        .info-box {
            margin-top: 20px;
            background-color: #F0F7FF;
            border: 1px solid #BAE6FD;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 11px;
            color: #475569;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .info-box i {
            color: #38BDF8;
            font-size: 14px;
            margin-top: 2px;
        }

        .error {
            color: #EF4444;
            font-size: 12px;
            margin-bottom: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo Sekolah -->
        <div class="logo">
            <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
        </div>

        <h1>OSIS SMK Pertiwi Kuningan</h1>
        <p class="subtitle">E-VOTING PEMILIHAN KETUA OSIS</p>
        <p class="tagline">"SUARAMU. MASA DEPAN SEKOLAH KITA."</p>

        <div class="login-card">
            <h2>Masuk sebagai Admin</h2>

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="/admin/login">
                @csrf

                <label>USERNAME</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-user icon-left"></i>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username">
                </div>

                <label>PASSWORD</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock icon-left"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="Masukkan password">
                    <button type="button" class="icon-right" onclick="togglePassword()">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <button type="submit" class="btn-submit" style="margin-top: 8px;">
                    <i class="fa-solid fa-arrow-right"></i> Masuk
                </button>
            </form>

            <div class="info-box">
                <i class="fa-solid fa-circle-info"></i>
                <div>
                    Gunakan akun secara bijak!
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'OSIS SMK Pertiwi Kuningan')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --sky:#38bdf8; --sky-dark:#0284c7; --navy:#075985; --bg:#f2f8fd; }
        * { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body { font-family:'Poppins',sans-serif; background:var(--bg); color:#1e293b; }
        section[id] { scroll-margin-top:90px; }

        /* Navbar pill melayang */
        .nav-wrap { position:fixed; top:14px; left:0; right:0; z-index:50; padding:0 4%; }
        .navbar { max-width:1200px; margin:0 auto; background:rgba(255,255,255,.93); backdrop-filter:blur(10px);
                  border-radius:999px; box-shadow:0 8px 28px rgba(7,89,133,.15);
                  display:flex; align-items:center; justify-content:space-between; gap:12px; padding:8px 10px 8px 20px; }
        .brand { display:flex; align-items:center; gap:10px; text-decoration:none; color:var(--navy); font-weight:700; flex-shrink:0; }
        .brand img { height:40px; }
        .nav-links { display:flex; gap:4px; list-style:none; align-items:center; overflow-x:auto; scrollbar-width:none; }
        .nav-links::-webkit-scrollbar { display:none; }
        .nav-links li { flex-shrink:0; }
        .nav-links a, .nav-links button { text-decoration:none; color:#475569; font-weight:500; font-family:inherit;
                       padding:9px 15px; border-radius:999px; font-size:14px; transition:.2s;
                       background:none; border:none; cursor:pointer; white-space:nowrap; }
        .nav-links a:hover { background:#e0f2fe; color:var(--sky-dark); }
        .nav-links .cta { background:linear-gradient(135deg,#00b4db,#0083b0); color:#fff; box-shadow:0 6px 16px rgba(0,131,176,.3); }

        .container { max-width:1100px; margin:0 auto; padding:0 5%; }
        .card { background:#fff; border-radius:22px; padding:26px; box-shadow:0 8px 24px rgba(2,132,199,.08); }

        /* Modal pemilihan */
        .modal { position:fixed; inset:0; background:rgba(7,89,133,.45); display:none;
                 align-items:center; justify-content:center; z-index:100; padding:20px; }
        .modal.open { display:flex; }
        .modal-box { background:#fff; border-radius:24px; padding:30px; width:100%; max-width:420px;
                     text-align:center; position:relative; box-shadow:0 20px 50px rgba(0,0,0,.2); }
        .modal-box h3 { color:var(--navy); margin-bottom:4px; }
        .modal-box > p { color:#64748b; font-size:13px; margin-bottom:20px; }
        .close { position:absolute; top:14px; right:18px; font-size:22px; cursor:pointer; color:#94a3b8; background:none; border:none; }
        .login-opt { display:flex; align-items:center; gap:14px; text-align:left; text-decoration:none;
                     padding:14px 16px; border-radius:16px; border:1px solid #e0f2fe; background:#f8fcff;
                     color:inherit; margin-bottom:12px; transition:.2s; }
        .login-opt:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(2,132,199,.15); }
        .login-opt .ic { width:46px; height:46px; border-radius:50%; background:#e0f2fe;
                         display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
        .login-opt b { color:var(--navy); display:block; font-size:15px; }
        .login-opt small { color:#64748b; font-size:12px; }

        footer { text-align:center; padding:30px; color:#94a3b8; font-size:13px; }

        @media (max-width:640px){
            .nav-wrap { top:8px; padding:0 3%; }
            .brand span { display:none; }
            .nav-links a, .nav-links button { padding:8px 11px; font-size:13px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="nav-wrap">
        <nav class="navbar">
            <a href="{{ route('landing') }}" class="brand">
                <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
                <span>OSIS SMK Pertiwi Kuningan</span>
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('landing') }}#beranda">Beranda</a></li>
                <li><a href="{{ route('landing') }}#tentang">Tentang</a></li>
                <li><a href="{{ route('landing') }}#program">Program</a></li>
                <li><a href="{{ route('landing') }}#acara">Acara</a></li>
                <li><a href="{{ route('landing') }}#kontak">Kontak</a></li>
                <li><button class="cta" onclick="toggleModal(true)">Pemilihan</button></li>
            </ul>
        </nav>
    </div>

    @yield('content')

    <footer>© {{ date('Y') }} OSIS SMK Pertiwi Kuningan</footer>

    <!-- Modal pilihan login -->
    <div class="modal" id="modalPemilihan" onclick="if(event.target===this)toggleModal(false)">
        <div class="modal-box">
            <button class="close" onclick="toggleModal(false)">&times;</button>
            <h3>E-Voting Pemilihan Ketua OSIS</h3>
            <p>Pilih jenis akun untuk masuk</p>

            <a href="{{ route('login') }}" class="login-opt">
                <div class="ic">🎓</div>
                <div><b>Login sebagai Siswa</b><small>Berikan suaramu untuk Ketua OSIS</small></div>
            </a>
            <a href="{{ route('admin.login') }}" class="login-opt">
                <div class="ic">🛡️</div>
                <div><b>Login sebagai Admin</b><small>Khusus panitia OSIS</small></div>
            </a>
        </div>
    </div>

    <script>
        function toggleModal(show){ document.getElementById('modalPemilihan').classList.toggle('open', show); }
        document.addEventListener('keydown', e => { if(e.key==='Escape') toggleModal(false); });
    </script>
</body>
</html>
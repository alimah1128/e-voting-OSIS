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

        /* ===== NAVBAR ===== */
        .nav-wrap { position:fixed; top:14px; left:0; right:0; z-index:50; padding:0 3%; }
        .navbar { position:relative; max-width:1240px; margin:0 auto; background:rgba(255,255,255,.95); backdrop-filter:blur(10px);
                  border-radius:999px; box-shadow:0 8px 28px rgba(7,89,133,.18);
                  display:flex; align-items:center; gap:16px; padding:10px 12px 10px 22px; }
        .brand { display:flex; align-items:center; gap:12px; text-decoration:none; flex-shrink:0; }
        .brand img { height:44px; }
        .brand-text { line-height:1.2; }
        .brand-text b { display:block; color:var(--navy); font-size:15px; font-weight:700; }
        .brand-text small { display:block; color:var(--sky-dark); font-size:12px; font-weight:500; }

        .nav-menu { flex:1; display:flex; align-items:center; justify-content:space-between; }
        .nav-links { display:flex; gap:4px; list-style:none; margin:0 auto; }
        .nav-links a { text-decoration:none; color:#475569; font-weight:500; padding:9px 16px;
                       border-radius:999px; font-size:14px; transition:.2s; display:block; }
        .nav-links a:hover, .nav-links a.active { background:#e0f2fe; color:var(--sky-dark); }
        .cta { background:linear-gradient(135deg,#00b4f0,#0090e0); color:#fff; border:none; cursor:pointer; font-family:inherit;
               font-size:14px; font-weight:600; padding:12px 22px; border-radius:999px;
               box-shadow:0 6px 16px rgba(0,144,224,.35); transition:.2s; white-space:nowrap; }
        .cta:hover { transform:translateY(-2px); }

        .burger { display:none; margin-left:auto; width:44px; height:44px; border-radius:50%; border:none; cursor:pointer;
                  background:#e0f2fe; color:var(--navy); font-size:22px; line-height:1; }

        .container { max-width:1100px; margin:0 auto; padding:0 5%; }
        .card { background:#fff; border-radius:22px; padding:26px; box-shadow:0 8px 24px rgba(2,132,199,.08); }

        /* ===== MODAL PEMILIHAN ===== */
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

        /* ===== HP / TABLET ===== */
        @media (max-width:900px){
            .nav-wrap { top:8px; padding:0 3%; }
            .navbar { padding:8px 10px 8px 16px; border-radius:30px; }
            .brand img { height:38px; }
            .brand-text b { font-size:14px; }
            .burger { display:block; }
            .nav-menu { display:none; position:absolute; top:calc(100% + 8px); left:0; right:0;
                        background:#fff; border-radius:26px; padding:14px; flex-direction:column; align-items:stretch;
                        gap:10px; box-shadow:0 14px 34px rgba(7,89,133,.22); }
            .nav-menu.open { display:flex; }
            .nav-links { flex-direction:column; margin:0; gap:2px; }
            .nav-links a { padding:12px 16px; font-size:15px; }
            .cta { width:100%; padding:14px; font-size:15px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="nav-wrap">
        <nav class="navbar">
            <a href="{{ route('landing') }}" class="brand">
                <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
                <div class="brand-text">
                    <b>OSIS SMK Pertiwi</b>
                    <small>Kuningan</small>
                </div>
            </a>

            <div class="nav-menu" id="navMenu">
                <ul class="nav-links">
                    <li><a href="{{ route('landing') }}#beranda">Beranda</a></li>
                    <li><a href="{{ route('landing') }}#tentang">Tentang</a></li>
                    <li><a href="{{ route('landing') }}#program">Program</a></li>
                    <li><a href="{{ route('landing') }}#acara">Acara</a></li>
                    <li><a href="{{ route('landing') }}#kontak">Kontak</a></li>
                </ul>
                <button class="cta" onclick="toggleModal(true)">Pemilihan ▾</button>
            </div>

            <button class="burger" id="burger" aria-label="Menu" onclick="toggleMenu()">☰</button>
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
        function toggleModal(show){
            document.getElementById('modalPemilihan').classList.toggle('open', show);
            if(show) document.getElementById('navMenu').classList.remove('open');
        }
        function toggleMenu(){
            var m = document.getElementById('navMenu');
            m.classList.toggle('open');
            document.getElementById('burger').textContent = m.classList.contains('open') ? '✕' : '☰';
        }
        // tutup menu HP setelah klik salah satu link
        document.querySelectorAll('.nav-links a').forEach(function(a){
            a.addEventListener('click', function(){
                document.getElementById('navMenu').classList.remove('open');
                document.getElementById('burger').textContent = '☰';
            });
        });
        document.addEventListener('keydown', function(e){ if(e.key==='Escape') toggleModal(false); });
    </script>
</body>
</html>
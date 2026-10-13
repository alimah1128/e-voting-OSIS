@php
    // Data umum untuk navbar & footer (dipakai di semua halaman publik)
    $site = \App\Models\Pengaturan::semua();

    // [nama route, pola route aktif, label, ikon]
    $menu = [
        ['landing',    'landing',  'Beranda', '🏠'],
        ['tentang',    'tentang',  'Tentang', '🎯'],
        ['program',    'program',  'Program', '📋'],
        ['acara.index','acara.*',  'Acara',   '📅'],
        ['kontak',     'kontak',   'Kontak',  '✉️'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'OSIS SMK Pertiwi Kuningan')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --sky:#38bdf8; --sky-dark:#0284c7; --navy:#075985; --deep:#053a5c; --bg:#f2f8fd; }
        * { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body { font-family:'Poppins',sans-serif; background:var(--bg); color:#1e293b; }
        body.no-scroll { overflow:hidden; }
        a:focus-visible, button:focus-visible { outline:3px solid #7dd3fc; outline-offset:2px; }
        section { padding:56px 0; }

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
        .nav-links a:hover { background:#e0f2fe; color:var(--sky-dark); }
        .nav-links a.active { background:#e0f2fe; color:var(--sky-dark); font-weight:600; }
        .cta { background:linear-gradient(135deg,#00b4f0,#0090e0); color:#fff; border:none; cursor:pointer; font-family:inherit;
               font-size:14px; font-weight:600; padding:12px 22px; border-radius:999px;
               box-shadow:0 6px 16px rgba(0,144,224,.35); transition:.2s; white-space:nowrap; }
        .cta:hover { transform:translateY(-2px); }

        .burger { display:none; margin-left:auto; width:44px; height:44px; border-radius:50%; border:none; cursor:pointer;
                  background:#e0f2fe; color:var(--navy); align-items:center; justify-content:center; }

        /* ===== DRAWER MENU HP (geser dari kanan) ===== */
        .drawer-overlay { position:fixed; inset:0; background:rgba(3,40,84,.5); backdrop-filter:blur(2px);
                          opacity:0; visibility:hidden; transition:opacity .3s, visibility 0s .3s; z-index:60; }
        .drawer-overlay.open { opacity:1; visibility:visible; transition:opacity .3s; }
        .drawer { position:fixed; top:0; right:0; bottom:0; width:min(86%,340px); background:#fff; z-index:70;
                  border-radius:28px 0 0 28px; box-shadow:-14px 0 40px rgba(7,89,133,.25);
                  display:flex; flex-direction:column; padding:18px 16px 20px;
                  transform:translateX(100%); visibility:hidden; transition:transform .35s cubic-bezier(.22,.8,.3,1), visibility 0s .35s; }
        .drawer.open { transform:none; visibility:visible; transition:transform .35s cubic-bezier(.22,.8,.3,1); }
        .drawer-head { display:flex; align-items:center; justify-content:space-between; padding:0 4px 16px;
                       border-bottom:1px solid #e0f2fe; margin-bottom:14px; }
        .drawer-close { width:40px; height:40px; border-radius:50%; border:none; background:#e0f2fe; color:var(--navy);
                        font-size:20px; cursor:pointer; line-height:1; }
        .drawer-links { list-style:none; display:flex; flex-direction:column; gap:4px; flex:1; overflow-y:auto; }
        .drawer-links a { display:flex; align-items:center; gap:14px; padding:11px 12px; border-radius:16px;
                          text-decoration:none; color:#334155; font-weight:500; font-size:15px; transition:.2s; }
        .drawer-links a .ic { width:38px; height:38px; border-radius:12px; background:#f0f9ff;
                              display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .drawer-links a:hover { background:#f0f9ff; }
        .drawer-links a.active { background:linear-gradient(135deg,#e0f2fe,#f0f9ff); color:var(--sky-dark); font-weight:600; }
        .drawer-links a.active .ic { background:linear-gradient(135deg,#00b4f0,#0090e0); }
        .drawer-foot { padding-top:14px; border-top:1px solid #e0f2fe; }
        .drawer-foot .cta { width:100%; padding:14px; font-size:15px; }
        .drawer-foot small { display:block; text-align:center; color:#94a3b8; font-size:12px; margin-top:10px; }

        /* ===== KOMPONEN BERSAMA ===== */
        .container { max-width:1100px; margin:0 auto; padding:0 5%; }
        .btn { display:inline-block; padding:14px 28px; border-radius:999px; font-weight:600; border:none; cursor:pointer;
               font-family:inherit; font-size:15px; color:#fff; background:linear-gradient(135deg,#00b4f0,#0090e0);
               box-shadow:0 8px 22px rgba(0,144,224,.4); transition:.2s; text-decoration:none; }
        .btn:hover { transform:translateY(-2px); }
        .btn-ghost { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.6); box-shadow:none; }
        .btn-ghost:hover { background:rgba(255,255,255,.2); }
        .btn-light { background:#fff; color:var(--navy); box-shadow:0 8px 22px rgba(0,0,0,.15); }
        .btn-outline { background:#fff; color:var(--sky-dark); border:1.5px solid #bae6fd; box-shadow:none; }
        .btn-outline:hover { background:#f0f9ff; }

        h2.title { text-align:center; color:var(--navy); margin-bottom:10px; font-size:clamp(22px,4vw,30px); }
        p.title-sub { text-align:center; color:#64748b; font-size:14px; margin-bottom:28px; }
        .sec-head { display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin-bottom:26px; flex-wrap:wrap; }
        .sec-head h2 { color:var(--navy); font-size:clamp(22px,4vw,30px); }
        .sec-head p { color:#64748b; font-size:14px; margin-top:4px; }
        .sec-head a { color:var(--sky-dark); font-weight:600; font-size:14px; text-decoration:none; }
        .sec-head a:hover { text-decoration:underline; }

        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:22px; }
        .card { background:#fff; border-radius:22px; padding:26px; box-shadow:0 8px 24px rgba(2,132,199,.08); }
        .card h3 { color:var(--navy); margin-bottom:8px; font-size:17px; }
        .card p, .card li { color:#64748b; font-size:14px; line-height:1.7; white-space:pre-line; }
        .card ul { padding-left:18px; }
        .card.hover { transition:.25s; }
        .card.hover:hover { transform:translateY(-6px); box-shadow:0 16px 34px rgba(2,132,199,.18); }
        .ikon { width:56px; height:56px; border-radius:18px; background:linear-gradient(135deg,#e0f2fe,#bae6fd);
                display:flex; align-items:center; justify-content:center; font-size:28px; margin-bottom:14px; }
        .pic { width:100%; height:160px; object-fit:cover; border-radius:14px; margin-bottom:12px; }
        .kosong { text-align:center; color:#94a3b8; padding:20px; }

        /* kartu acara / artikel */
        .band { background:#e6f4fc; border-radius:40px; }
        .art { background:#fff; border-radius:24px; overflow:hidden; box-shadow:0 10px 26px rgba(2,132,199,.1);
               display:flex; flex-direction:column; transition:.25s; text-decoration:none; color:inherit; }
        .art:hover { transform:translateY(-6px); box-shadow:0 18px 38px rgba(2,132,199,.2); }
        .art-img { position:relative; height:180px; background:linear-gradient(135deg,#bae6fd,#7dd3fc);
                   display:flex; align-items:center; justify-content:center; font-size:48px; }
        .art-img img { width:100%; height:100%; object-fit:cover; display:block; }
        .art-tag { position:absolute; top:12px; left:12px; background:#fff; color:var(--sky-dark); font-size:11px; font-weight:700;
                   padding:5px 12px; border-radius:999px; box-shadow:0 4px 10px rgba(0,0,0,.1); }
        .art-tag.selesai { color:#64748b; }
        .art-body { padding:20px; display:flex; flex-direction:column; flex:1; }
        .art-date { font-size:12px; color:#94a3b8; margin-bottom:6px; }
        .art-body h3 { color:var(--navy); font-size:17px; margin-bottom:8px; line-height:1.4; }
        .art-body p { color:#64748b; font-size:13.5px; line-height:1.7; flex:1; margin-bottom:16px; }
        .art-btn { align-self:flex-start; background:linear-gradient(135deg,#00b4db,#0083b0); color:#fff; font-size:13px; font-weight:600;
                   padding:9px 20px; border-radius:999px; }

        /* banner halaman dalam */
        .page-banner { position:relative; padding:150px 0 64px; color:#fff; overflow:hidden;
                       background:linear-gradient(135deg,var(--deep),var(--sky-dark)); border-radius:0 0 36px 36px; }
        .page-banner::before { content:''; position:absolute; width:380px; height:380px; border-radius:50%;
                       right:-90px; top:-120px; background:rgba(125,211,252,.16); }
        .page-banner::after { content:''; position:absolute; width:220px; height:220px; border-radius:50%;
                       right:150px; bottom:-130px; background:rgba(255,255,255,.07); }
        .page-banner .container { position:relative; z-index:2; }
        .crumb { font-size:13px; opacity:.85; margin-bottom:12px; }
        .crumb a { color:#bae6fd; text-decoration:none; }
        .crumb a:hover { text-decoration:underline; }
        .page-banner h1 { font-size:clamp(28px,5vw,44px); font-weight:700; line-height:1.2; max-width:760px; }
        .page-banner p { margin-top:12px; max-width:560px; line-height:1.8; opacity:.92; font-size:15px; }

        /* ===== FOOTER ===== */
        .footer { margin-top:60px; background:linear-gradient(135deg,var(--deep),var(--navy)); color:#cfe8f7;
                  border-radius:40px 40px 0 0; padding-top:50px; }
        .foot-grid { display:grid; grid-template-columns:1.4fr 1fr 1.2fr; gap:36px; padding-bottom:34px; }
        .foot-brand { display:flex; align-items:center; gap:12px; margin-bottom:14px; }
        .foot-brand img { height:48px; background:#fff; border-radius:50%; padding:3px; }
        .foot-brand b { color:#fff; display:block; font-size:16px; }
        .foot-brand small { color:#7dd3fc; font-size:12px; }
        .footer p { font-size:13.5px; line-height:1.8; }
        .footer h4 { color:#fff; font-size:15px; margin-bottom:14px; }
        .foot-links { list-style:none; display:flex; flex-direction:column; gap:9px; }
        .foot-links a { color:#cfe8f7; text-decoration:none; font-size:14px; }
        .foot-links a:hover { color:#fff; text-decoration:underline; }
        .foot-contact p { margin-bottom:8px; }
        .foot-bottom { border-top:1px solid rgba(255,255,255,.14); text-align:center; padding:18px 5%; font-size:13px; color:#9fcbe6; }

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

        /* ===== HP / TABLET ===== */
        @media (max-width:900px){
            .nav-wrap { top:8px; padding:0 3%; }
            .navbar { padding:8px 10px 8px 16px; border-radius:30px; }
            .brand img { height:38px; }
            .brand-text b { font-size:14px; }
            .nav-menu { display:none; }
            .burger { display:flex; }
            .page-banner { padding:116px 0 48px; border-radius:0 0 26px 26px; }
            .foot-grid { grid-template-columns:1fr; gap:28px; }
            .footer { border-radius:28px 28px 0 0; }
        }
        @media (prefers-reduced-motion:reduce){
            html { scroll-behavior:auto; }
            *, *::before, *::after { transition-duration:.01ms !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- ===== NAVBAR ===== --}}
    <div class="nav-wrap">
        <nav class="navbar">
            <a href="{{ route('landing') }}" class="brand">
                <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
                <div class="brand-text">
                    <b>OSIS SMK Pertiwi</b>
                    <small>Kuningan</small>
                </div>
            </a>

            <div class="nav-menu">
                <ul class="nav-links">
                    @foreach($menu as [$rt, $pola, $label, $ikon])
                        <li><a href="{{ route($rt) }}" class="{{ request()->routeIs($pola) ? 'active' : '' }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
                <button class="cta" onclick="toggleModal(true)">Pemilihan ▾</button>
            </div>

            <button class="burger" id="burger" aria-label="Buka menu" aria-expanded="false" onclick="openDrawer()">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                    <path d="M4 7h16M4 12h16M4 17h10"/>
                </svg>
            </button>
        </nav>
    </div>

    {{-- ===== DRAWER MENU HP ===== --}}
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
    <aside class="drawer" id="drawer" aria-label="Menu navigasi">
        <div class="drawer-head">
            <a href="{{ route('landing') }}" class="brand">
                <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
                <div class="brand-text">
                    <b>OSIS SMK Pertiwi</b>
                    <small>Kuningan</small>
                </div>
            </a>
            <button class="drawer-close" aria-label="Tutup menu" onclick="closeDrawer()">&times;</button>
        </div>

        <ul class="drawer-links">
            @foreach($menu as [$rt, $pola, $label, $ikon])
                <li>
                    <a href="{{ route($rt) }}" class="{{ request()->routeIs($pola) ? 'active' : '' }}">
                        <span class="ic">{{ $ikon }}</span>{{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="drawer-foot">
            <button class="cta" onclick="closeDrawer(); toggleModal(true)">Pemilihan ▾</button>
            <small>© {{ date('Y') }} OSIS SMK Pertiwi Kuningan</small>
        </div>
    </aside>

    @yield('content')

    {{-- ===== FOOTER ===== --}}
    <footer class="footer">
        <div class="container foot-grid">
            <div>
                <div class="foot-brand">
                    <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
                    <div><b>OSIS SMK Pertiwi</b><small>Kuningan</small></div>
                </div>
                <p>Organisasi Siswa Intra Sekolah SMK Pertiwi Kuningan.</p>
            </div>

            <div>
                <h4>Menu</h4>
                <ul class="foot-links">
                    @foreach($menu as [$rt, $pola, $label, $ikon])
                        <li><a href="{{ route($rt) }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="foot-contact">
                <h4>Kontak</h4>
                <p>📍 {{ $site['kontak_alamat'] ?? '-' }}</p>
                <p>✉️ {{ $site['kontak_email'] ?? '-' }}</p>
                <p>📷 {{ $site['kontak_instagram'] ?? '-' }}</p>
            </div>
        </div>
        <div class="foot-bottom">© {{ date('Y') }} OSIS SMK Pertiwi Kuningan</div>
    </footer>

    {{-- ===== MODAL PILIHAN LOGIN ===== --}}
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
        }
        function openDrawer(){
            document.getElementById('drawer').classList.add('open');
            document.getElementById('drawerOverlay').classList.add('open');
            document.getElementById('burger').setAttribute('aria-expanded','true');
            document.body.classList.add('no-scroll');
        }
        function closeDrawer(){
            document.getElementById('drawer').classList.remove('open');
            document.getElementById('drawerOverlay').classList.remove('open');
            document.getElementById('burger').setAttribute('aria-expanded','false');
            document.body.classList.remove('no-scroll');
        }
        document.addEventListener('keydown', function(e){
            if(e.key==='Escape'){ toggleModal(false); closeDrawer(); }
        });
        // kalau layar dilebarkan (misal HP diputar), tutup drawer otomatis
        window.addEventListener('resize', function(){ if(window.innerWidth > 900) closeDrawer(); });
    </script>
    @stack('scripts')
</body>
</html>
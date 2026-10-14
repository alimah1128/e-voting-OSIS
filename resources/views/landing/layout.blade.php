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

    // Kontak untuk footer (yang kosong tidak ditampilkan)
    $kontakFooter = array_filter([
        '📍' => $site['kontak_alamat']    ?? '',
        '✉️' => $site['kontak_email']     ?? '',
        '📷' => $site['kontak_instagram'] ?? '',
    ]);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'OSIS SMK Pertiwi Kuningan')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --sky:#38bdf8; --sky-dark:#0284c7; --brand:#0369a1; --navy:#075985; --deep:#053a5c; --bg:#eef6fd; --soft:#e0f2fe; }
        * { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        /* ← BARU: body setinggi layar, footer didorong ke bawah */
        body { font-family:'Poppins',sans-serif; background:var(--bg); color:#1e293b;
               min-height:100vh; min-height:100dvh; display:flex; flex-direction:column; }
        body.no-scroll { overflow:hidden; }
        a:focus-visible, button:focus-visible { outline:3px solid #7dd3fc; outline-offset:2px; }
        section { padding:32px 0; }

        /* ===== NAVBAR ===== */
        .nav-wrap { position:fixed; top:10px; left:0; right:0; z-index:50; padding:0 3%; }
        .navbar { position:relative; max-width:1100px; margin:0 auto; background:rgba(255,255,255,.96); backdrop-filter:blur(10px);
                  border-radius:999px; box-shadow:0 4px 16px rgba(3,105,161,.12);
                  display:flex; align-items:center; gap:12px; padding:6px 8px 6px 16px; }
        .brand { display:flex; align-items:center; gap:10px; text-decoration:none; flex-shrink:0; }
        .brand img { height:36px; }
        .brand-text { line-height:1.2; }
        .brand-text b { display:block; color:var(--brand); font-size:14px; font-weight:700; }
        .brand-text small { display:block; color:var(--sky-dark); font-size:11px; font-weight:500; }

        .nav-menu { flex:1; display:flex; align-items:center; justify-content:space-between; }
        .nav-links { display:flex; gap:2px; list-style:none; margin:0 auto; }
        .nav-links a { text-decoration:none; color:#475569; font-weight:500; padding:7px 14px;
                       border-radius:999px; font-size:13.5px; transition:.2s; display:block; }
        .nav-links a:hover { background:var(--soft); color:var(--brand); }
        .nav-links a.active { background:var(--soft); color:var(--brand); font-weight:600; }
        .cta { background:var(--brand); color:#fff; border:none; cursor:pointer; font-family:inherit;
               font-size:13.5px; font-weight:600; padding:9px 18px; border-radius:999px;
               box-shadow:0 4px 10px rgba(3,105,161,.25); transition:.2s; white-space:nowrap; }
        .cta:hover { background:var(--sky-dark); transform:translateY(-1px); }

        .burger { display:none; margin-left:auto; width:38px; height:38px; border-radius:50%; border:none; cursor:pointer;
                  background:var(--soft); color:var(--brand); align-items:center; justify-content:center; }

        /* ===== DRAWER MENU HP (geser dari kanan) ===== */
        .drawer-overlay { position:fixed; inset:0; background:rgba(3,40,84,.45); backdrop-filter:blur(2px);
                          opacity:0; visibility:hidden; transition:opacity .3s, visibility 0s .3s; z-index:60; }
        .drawer-overlay.open { opacity:1; visibility:visible; transition:opacity .3s; }
        .drawer { position:fixed; top:0; right:0; bottom:0; width:min(80%,300px); background:#fff; z-index:70;
                  border-radius:24px 0 0 24px; box-shadow:-12px 0 36px rgba(7,89,133,.22);
                  display:flex; flex-direction:column; padding:14px 14px 16px;
                  transform:translateX(100%); visibility:hidden; transition:transform .35s cubic-bezier(.22,.8,.3,1), visibility 0s .35s; }
        .drawer.open { transform:none; visibility:visible; transition:transform .35s cubic-bezier(.22,.8,.3,1); }
        .drawer-head { display:flex; align-items:center; justify-content:space-between; padding:0 4px 12px;
                       border-bottom:1px solid var(--soft); margin-bottom:10px; }
        .drawer-close { width:36px; height:36px; border-radius:50%; border:none; background:var(--soft); color:var(--brand);
                        font-size:20px; cursor:pointer; line-height:1; }
        .drawer-links { list-style:none; display:flex; flex-direction:column; gap:2px; flex:1; overflow-y:auto; }
        .drawer-links a { display:flex; align-items:center; gap:12px; padding:8px 10px; border-radius:14px;
                          text-decoration:none; color:#334155; font-weight:500; font-size:14px; transition:.2s; }
        .drawer-links a .ic { width:34px; height:34px; border-radius:11px; background:#f0f9ff;
                              display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
        .drawer-links a:hover { background:#f0f9ff; }
        .drawer-links a.active { background:var(--soft); color:var(--brand); font-weight:600; }
        .drawer-links a.active .ic { background:#fff; }
        .drawer-foot { padding-top:12px; border-top:1px solid var(--soft); }
        .drawer-foot .cta { width:100%; padding:11px; font-size:14px; }
        .drawer-foot small { display:block; text-align:center; color:#94a3b8; font-size:11.5px; margin-top:8px; }

        /* ===== KOMPONEN BERSAMA ===== */
        /* ← BARU: width:100% supaya tidak menyusut di dalam body flex */
        .container { width:100%; max-width:1100px; margin:0 auto; padding:0 5%; }
        .btn { display:inline-block; padding:10px 22px; border-radius:999px; font-weight:600; border:none; cursor:pointer;
               font-family:inherit; font-size:14px; color:#fff; background:var(--brand);
               box-shadow:0 4px 12px rgba(3,105,161,.25); transition:.2s; text-decoration:none; }
        .btn:hover { background:var(--sky-dark); transform:translateY(-1px); }
        .btn-ghost { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.6); box-shadow:none; }
        .btn-ghost:hover { background:rgba(255,255,255,.22); }
        .btn-light { background:#fff; color:var(--brand); box-shadow:0 4px 12px rgba(0,0,0,.12); }
        .btn-light:hover { background:#f0f9ff; }
        .btn-outline { background:#fff; color:var(--sky-dark); border:1.5px solid #bae6fd; box-shadow:none; }
        .btn-outline:hover { background:#f0f9ff; }

        h2.title { text-align:center; color:var(--brand); margin-bottom:6px; font-size:clamp(20px,3.5vw,26px); }
        p.title-sub { text-align:center; color:#64748b; font-size:13.5px; margin-bottom:20px; }
        .sec-head { display:flex; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:18px; flex-wrap:wrap; }
        .sec-head h2 { color:var(--brand); font-size:clamp(20px,3.5vw,26px); }
        .sec-head p { color:#64748b; font-size:13.5px; margin-top:2px; }
        .sec-head a { color:var(--sky-dark); font-weight:600; font-size:13.5px; text-decoration:none; }
        .sec-head a:hover { text-decoration:underline; }

        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:16px; }
        .card { background:#fff; border-radius:18px; padding:20px; box-shadow:0 4px 16px rgba(2,132,199,.07); }
        .card h3 { color:var(--brand); margin-bottom:6px; font-size:16px; }
        .card p, .card li { color:#64748b; font-size:13.5px; line-height:1.65; white-space:pre-line; }
        .card ul { padding-left:18px; }
        .card.hover { transition:.25s; }
        .card.hover:hover { transform:translateY(-3px); box-shadow:0 10px 24px rgba(2,132,199,.14); }
        .ikon { width:44px; height:44px; border-radius:14px; background:var(--soft);
                display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:10px; }
        .pic { width:100%; height:140px; object-fit:cover; border-radius:12px; margin-bottom:10px; }
        .kosong { text-align:center; color:#94a3b8; padding:14px; font-size:14px; }

        /* kartu acara / artikel */
        .band { background:#e6f4fc; border-radius:28px; }
        .art { background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 4px 16px rgba(2,132,199,.08);
               display:flex; flex-direction:column; transition:.25s; text-decoration:none; color:inherit; }
        .art:hover { transform:translateY(-3px); box-shadow:0 10px 24px rgba(2,132,199,.16); }
        .art-img { position:relative; height:140px; background:linear-gradient(135deg,#bae6fd,#7dd3fc);
                   display:flex; align-items:center; justify-content:center; font-size:40px; }
        .art-img img { width:100%; height:100%; object-fit:cover; display:block; }
        .art-tag { position:absolute; top:10px; left:10px; background:#fff; color:var(--sky-dark); font-size:11px; font-weight:700;
                   padding:4px 10px; border-radius:999px; box-shadow:0 3px 8px rgba(0,0,0,.1); }
        .art-tag.selesai { color:#64748b; }
        .art-body { padding:16px; display:flex; flex-direction:column; flex:1; }
        .art-date { font-size:11.5px; color:#94a3b8; margin-bottom:4px; }
        .art-body h3 { color:var(--brand); font-size:16px; margin-bottom:6px; line-height:1.4; }
        .art-body p { color:#64748b; font-size:13px; line-height:1.6; flex:1; margin-bottom:12px; }
        .art-btn { align-self:flex-start; background:var(--brand); color:#fff; font-size:12.5px; font-weight:600;
                   padding:7px 16px; border-radius:999px; }

        /* banner halaman dalam (terang & pendek) */
        .page-banner { position:relative; padding:92px 0 26px; color:var(--navy); overflow:hidden;
                       background:linear-gradient(135deg,#e0f2fe,#f4faff); border-radius:0 0 28px 28px;
                       box-shadow:0 4px 18px rgba(3,105,161,.06); }
        .page-banner::before { content:''; position:absolute; width:220px; height:220px; border-radius:50%;
                       right:-60px; top:-90px; background:rgba(56,189,248,.15); }
        .page-banner .container { position:relative; z-index:2; }
        .crumb { font-size:12.5px; color:#64748b; margin-bottom:6px; }
        .crumb a { color:var(--sky-dark); text-decoration:none; }
        .crumb a:hover { text-decoration:underline; }
        .page-banner h1 { font-size:clamp(22px,4vw,32px); font-weight:700; line-height:1.25; max-width:760px; color:var(--brand); }
        .page-banner p { margin-top:6px; max-width:560px; line-height:1.7; color:#475569; font-size:14px; }

        /* ===== FOOTER (ramping, selalu menempel di bawah) ===== */
        /* ← BARU: margin-top:auto mendorong footer ke dasar layar */
        .footer { margin-top:auto; background:#fff; border-radius:24px 24px 0 0; padding-top:16px;
                  box-shadow:0 -4px 18px rgba(3,105,161,.07); color:#475569; }
        .foot-top { display:flex; align-items:center; justify-content:space-between; gap:10px 24px; flex-wrap:wrap; }
        .foot-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .foot-brand img { height:34px; }
        .foot-brand b { display:block; color:var(--brand); font-size:14px; line-height:1.2; }
        .foot-brand small { color:var(--sky-dark); font-size:11.5px; }
        .foot-links { list-style:none; display:flex; flex-wrap:wrap; gap:2px 4px; }
        .foot-links a { color:#475569; text-decoration:none; font-size:13px; padding:5px 11px; border-radius:999px; transition:.2s; display:block; }
        .foot-links a:hover { background:var(--soft); color:var(--brand); }
        .foot-contact { display:flex; flex-wrap:wrap; gap:4px 18px; font-size:12.5px; color:#64748b; margin-top:10px; }
        .foot-bottom { border-top:1px solid var(--soft); text-align:center; padding:10px 5%; margin-top:12px;
                       font-size:12px; color:#94a3b8; }

        /* ===== MODAL PEMILIHAN ===== */
        .modal { position:fixed; inset:0; background:rgba(7,89,133,.4); display:none;
                 align-items:center; justify-content:center; z-index:100; padding:20px; }
        .modal.open { display:flex; }
        .modal-box { background:#fff; border-radius:22px; padding:24px; width:100%; max-width:400px;
                     text-align:center; position:relative; box-shadow:0 20px 50px rgba(0,0,0,.2); }
        .modal-box h3 { color:var(--brand); margin-bottom:4px; font-size:17px; }
        .modal-box > p { color:#64748b; font-size:13px; margin-bottom:16px; }
        .close { position:absolute; top:12px; right:16px; font-size:22px; cursor:pointer; color:#94a3b8; background:none; border:none; }
        .login-opt { display:flex; align-items:center; gap:12px; text-align:left; text-decoration:none;
                     padding:11px 14px; border-radius:14px; border:1px solid var(--soft); background:#f8fcff;
                     color:inherit; margin-bottom:10px; transition:.2s; }
        .login-opt:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(2,132,199,.14); }
        .login-opt .ic { width:40px; height:40px; border-radius:50%; background:var(--soft);
                         display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
        .login-opt b { color:var(--brand); display:block; font-size:14.5px; }
        .login-opt small { color:#64748b; font-size:12px; }

        /* ===== HP / TABLET ===== */
        @media (max-width:900px){
            section { padding:24px 0; }
            .nav-wrap { top:6px; padding:0 3%; }
            .navbar { padding:6px 8px 6px 14px; }
            .brand img { height:32px; }
            .brand-text b { font-size:13.5px; }
            .nav-menu { display:none; }
            .burger { display:flex; }
            .page-banner { padding:78px 0 20px; border-radius:0 0 22px 22px; }
            .footer { border-radius:20px 20px 0 0; }
            .foot-top { flex-direction:column; align-items:center; text-align:center; gap:8px; }
            .foot-links { justify-content:center; }
            .foot-contact { justify-content:center; text-align:center; }
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
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
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
        <div class="container">
            <div class="foot-top">
                <a href="{{ route('landing') }}" class="foot-brand">
                    <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
                    <div><b>OSIS SMK Pertiwi</b><small>Kuningan</small></div>
                </a>

                <ul class="foot-links">
                    @foreach($menu as [$rt, $pola, $label, $ikon])
                        <li><a href="{{ route($rt) }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            @if(count($kontakFooter))
                <div class="foot-contact">
                    @foreach($kontakFooter as $ik => $nilai)
                        <span>{{ $ik }} {{ $nilai }}</span>
                    @endforeach
                </div>
            @endif
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
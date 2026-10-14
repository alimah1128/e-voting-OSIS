@php
    $adminProfil = \App\Models\Admin::find(session('admin_id'));
    $namaAdmin   = session('admin_nama') ?: session('admin_username');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Admin OSIS SMK Pertiwi Kuningan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#05689f; --navy:#0b5a85; --soft:#e0f2fe; --bg:#f0f7ff; --line:#e2e8f0; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Poppins',sans-serif; background:var(--bg); color:#1e293b; min-height:100vh; }
        a { color:inherit; }
        [hidden] { display:none !important; }
        a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible, select:focus-visible { outline:3px solid #7dd3fc; outline-offset:2px; }

        /* ===== NAVBAR ATAS ===== */
        .topwrap { padding:10px 28px 0; }
        .topbar { max-width:1280px; margin:0 auto; background:#fff; border-radius:999px; box-shadow:0 8px 26px rgba(7,89,133,.12);
                  display:grid; grid-template-columns:1fr auto 1fr; align-items:center; gap:12px; padding:8px 14px 8px 12px; }
        .brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        /* logo tampil utuh (tidak dipotong bulat) */
        .brand img { height:46px; width:auto; max-width:52px; object-fit:contain; flex-shrink:0; }
        .brand b { display:block; color:var(--navy); font-size:14px; line-height:1.2; }
        .brand small { display:block; color:#0284c7; font-size:12px; }
        .pills { display:flex; gap:10px; justify-content:center; }
        .pill { padding:10px 28px; border-radius:999px; border:2px solid #bae6fd; color:var(--navy); font-weight:600; font-size:14px;
                text-decoration:none; transition:.2s; background:#fff; }
        .pill:hover { background:#f0f9ff; }
        .pill.on { background:var(--primary); border-color:var(--primary); color:#fff; }
        .user { display:flex; align-items:center; gap:10px; justify-self:end; }
        .user .who { text-align:right; line-height:1.3; }
        .user .who b { display:block; font-size:13px; color:var(--navy); font-weight:600; }
        .user .who a { font-size:12px; color:#b91c1c; text-decoration:none; }
        .user .who a:hover { text-decoration:underline; }
        .avatar { width:40px; height:40px; border-radius:50%; background:var(--navy); color:#fff; font-weight:700; font-size:14px;
                  display:flex; align-items:center; justify-content:center; flex-shrink:0; border:none; padding:0; cursor:pointer;
                  overflow:hidden; font-family:inherit; transition:transform .2s ease, box-shadow .2s ease; }
        .avatar:hover { transform:scale(1.06); box-shadow:0 4px 12px rgba(7,89,133,.3); }
        .avatar img { width:100%; height:100%; object-fit:cover; }

        /* ===== SUB-TAB ===== */
        .subnav { max-width:1280px; margin:14px auto 0; background:#fff; border-radius:16px; padding:8px;
                  display:flex; gap:8px; overflow-x:auto; box-shadow:0 4px 16px rgba(2,132,199,.06); }
        .subnav a { padding:9px 18px; border-radius:12px; background:#eef3f9; color:var(--navy); font-weight:600; font-size:13.5px;
                    text-decoration:none; white-space:nowrap; transition:.2s; }
        .subnav a:hover { background:#dbeafe; }
        .subnav a.on { background:var(--primary); color:#fff; }

        /* ===== ISI HALAMAN ===== */
        .page { max-width:1280px; margin:0 auto; padding:22px 28px 70px; }
        .page-head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:20px; }
        .page-title { font-size:26px; color:var(--navy); font-weight:700; line-height:1.25; }
        .page-sub { color:#64748b; font-size:14px; margin-top:2px; }

        .card { background:#fff; border-radius:22px; padding:24px; box-shadow:0 6px 22px rgba(2,132,199,.07); }
        .card h3 { font-size:15px; color:var(--navy); font-weight:600; }
        .card-head { display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
        .split { display:grid; grid-template-columns:minmax(0,1fr) 360px; gap:20px; align-items:start; }
        .two { display:grid; grid-template-columns:1fr 1fr; gap:28px; }

        .alert { background:#dcfce7; color:#166534; padding:12px 18px; border-radius:14px; margin-bottom:18px; font-size:14px; }
        .err { background:#fee2e2; color:#991b1b; padding:12px 18px; border-radius:14px; margin-bottom:18px; font-size:13px; }

        /* tombol */
        .btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:999px; border:none; cursor:pointer;
               font-family:inherit; font-size:13px; font-weight:600; text-decoration:none; background:#e2e8f0; color:#334155; transition:.2s; }
        .btn:hover { filter:brightness(.97); transform:translateY(-1px); }
        .btn-primary { background:var(--primary); color:#fff; }
        .btn-soft { background:var(--soft); color:#075985; }
        .btn-danger { background:#fee2e2; color:#b91c1c; }
        .btn-outline { background:#fff; border:1.5px solid #bae6fd; color:var(--navy); }
        .btn-sm { padding:6px 14px; font-size:12px; }
        .actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
        .actions form { display:inline; }

        /* tabel */
        .tbl-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:14px; }
        th { text-align:left; color:#64748b; font-size:11px; text-transform:uppercase; letter-spacing:.04em; padding:10px; border-bottom:1px solid var(--line); }
        td { padding:12px 10px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr.row-on td { background:#f0f9ff; }
        .judul b { color:#0f172a; display:block; font-size:14px; }
        .judul small { color:#94a3b8; font-size:12px; }
        .thumb { width:44px; height:44px; border-radius:10px; object-fit:cover; display:flex; align-items:center; justify-content:center; font-size:20px; }
        .ph { background:repeating-linear-gradient(135deg,#d6ebfa 0 14px,#e8f4fc 14px 28px); }
        .badge { display:inline-block; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; white-space:nowrap; }
        .b-aktif { background:#dcfce7; color:#166534; }
        .b-non { background:#e2e8f0; color:#475569; }
        .b-akan { background:#e0f2fe; color:#075985; }
        .b-selesai { background:#e2e8f0; color:#475569; }
        .kosong { text-align:center; color:#94a3b8; padding:30px 10px; }

        /* form */
        .fg { margin-bottom:16px; }
        .fg label.lb { display:block; font-size:11px; font-weight:600; color:#475569; margin-bottom:6px; text-transform:uppercase; letter-spacing:.04em; }
        .fg input[type=text], .fg input[type=email], .fg input[type=date], .fg input[type=number], .fg select, .fg textarea {
            width:100%; padding:11px 14px; border:1px solid var(--line); border-radius:12px; font-family:inherit; font-size:14px; background:#f8fafc; color:#1e293b; }
        .fg textarea { min-height:100px; resize:vertical; line-height:1.6; }
        .fg input:focus, .fg textarea:focus, .fg select:focus { outline:none; border-color:#38bdf8; background:#fff; }
        .fg small { color:#94a3b8; font-size:12px; display:block; margin-top:5px; }
        .row2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .sel-on { background:#dcfce7 !important; color:#166534; font-weight:600; border-color:#bbf7d0 !important; }

        /* kotak foto */
        .photo-box { display:flex; align-items:center; justify-content:center; width:100%; aspect-ratio:16/7; border-radius:14px; cursor:pointer;
                     background-size:cover; background-position:center; margin-bottom:12px; position:relative; overflow:hidden;
                     font-family:ui-monospace,Menlo,monospace; font-size:11px; color:#0b5a85; }
        .photo-box.has-img { font-size:0; }
        .photo-box.big { aspect-ratio:4/3; }


        /* ===== MODAL KONFIRMASI LOGOUT ===== */
        .mlogout { position:fixed; inset:0; background:rgba(15,40,70,.5); display:none; align-items:center; justify-content:center; z-index:200; padding:20px; }
        .mlogout.open { display:flex; }
        .mlogout-box { background:#fff; border-radius:24px; padding:30px 34px; width:100%; max-width:380px; text-align:center; box-shadow:0 24px 60px rgba(0,0,0,.25); }
        .mlogout-ic { width:56px; height:56px; border-radius:50%; background:#dbeafe; color:var(--navy); display:flex; align-items:center; justify-content:center; font-size:22px; margin:0 auto 14px; }
        .mlogout-box h3 { color:#0f172a; font-size:17px; margin-bottom:6px; }
        .mlogout-box p { color:#64748b; font-size:13.5px; margin-bottom:20px; }
        .mlogout-box .actions { justify-content:center; }
        .mlogout-box .btn { padding:11px 26px; }

        /* ===== MODAL PROFIL ADMIN ===== */
        .mprofil { position:fixed; inset:0; background:rgba(15,40,70,.5); display:none; align-items:center; justify-content:center; z-index:210; padding:16px; }
        .mprofil.open { display:flex; }
        .mprofil-box { background:#fff; border-radius:24px; padding:28px 30px; width:100%; max-width:480px; max-height:90vh; overflow-y:auto;
                       box-shadow:0 24px 60px rgba(0,0,0,.25); }
        .mprofil-box h3 { color:var(--navy); font-size:18px; font-weight:700; }
        .mp-sub { color:#64748b; font-size:13px; margin:2px 0 18px; }
        .mp-ok { background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; border-radius:12px; padding:11px 14px; font-size:13px; margin-bottom:14px; }
        .mp-err { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; border-radius:12px; padding:11px 14px; font-size:13px; margin-bottom:14px; }
        .mp-err ul { margin:6px 0 0 18px; }
        .mp-fotowrap { text-align:center; margin-bottom:16px; }
        .mp-fotoarea { position:relative; display:inline-block; }
        .mp-foto { width:96px; height:96px; border-radius:50%; border:2px dashed #bae6fd; background:#f0f9ff; cursor:pointer;
                   display:flex; align-items:center; justify-content:center; overflow:hidden; color:#94a3b8; font-size:12px; text-align:center;
                   line-height:1.3; padding:8px; transition:.2s; }
        .mp-foto:hover { border-color:#38bdf8; background:#e0f2fe; }
        .mp-foto img { width:100%; height:100%; object-fit:cover; border-radius:50%; margin:-8px; padding:0; }
        .mp-hapus { position:absolute; top:-2px; right:-2px; width:26px; height:26px; border-radius:50%; background:#dc2626; color:#fff;
                    border:2px solid #fff; cursor:pointer; font-size:13px; line-height:1; display:flex; align-items:center; justify-content:center;
                    box-shadow:0 2px 6px rgba(0,0,0,.15); transition:.15s; }
        .mp-hapus:hover { background:#b91c1c; transform:scale(1.1); }
        .mp-hint { font-size:12px; color:#94a3b8; margin-top:8px; }
        .mp-passwrap { position:relative; }
        .mp-passwrap input { padding-right:78px !important; }
        .mp-eye { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer;
                  color:#0284c7; font-family:inherit; font-size:12px; font-weight:600; padding:4px 6px; }
        .mp-actions { display:flex; gap:10px; margin-top:6px; }

        @media (max-width:960px){
            .split { grid-template-columns:1fr; }
            .two { grid-template-columns:1fr; gap:10px; }
        }
        @media (max-width:760px){
            .topwrap { padding:8px 12px 0; }
            .topbar { grid-template-columns:1fr auto; border-radius:26px; padding:8px 12px; }
            .pills { grid-column:1 / -1; grid-row:2; }
            .pills .pill { flex:1; text-align:center; padding:9px 12px; }
            .user .who b { display:none; }
            .page { padding:18px 14px 60px; }
            .page-title { font-size:22px; }
            .card { padding:18px; }
            .row2 { grid-template-columns:1fr; }
            .mprofil-box { padding:22px 18px; }
        }
        @media (prefers-reduced-motion:reduce){ *, *::before, *::after { transition-duration:.01ms !important; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="topwrap">
        <header class="topbar">
            <a href="{{ route('admin.beranda') }}" class="brand">
                <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
                <div><b>OSIS SMK Pertiwi</b><small>Kuningan</small></div>
            </a>

            @php
                // halaman e-voting => tombol "Pemilihan" yang menyala
                $modePemilihan = request()->routeIs('admin.dashboard', 'admin.siswa.*', 'admin.kandidat.*', 'admin.periode.*', 'admin.hasil.*', 'admin.belumMemilih');
            @endphp
            <nav class="pills">
                <a href="{{ route('admin.beranda') }}" class="pill {{ $modePemilihan ? '' : 'on' }}">Dashboard</a>
                <a href="{{ route('admin.dashboard') }}" class="pill {{ $modePemilihan ? 'on' : '' }}">Pemilihan</a>
            </nav>

            <div class="user">
                <div class="who">
                    <b>{{ $namaAdmin }}</b>
                    <a href="#" onclick="event.preventDefault(); bukaModalLogout();">Logout</a>
                </div>
                <button type="button" class="avatar" onclick="bukaModalProfil()" title="Profil Admin" aria-label="Buka profil admin">
                    @if (session('admin_foto'))
                        <img src="{{ asset('image/' . session('admin_foto')) }}" alt="Foto Admin">
                    @else
                        {{ strtoupper(substr($namaAdmin, 0, 1)) }}
                    @endif
                </button>
            </div>
        </header>

        @include('admin.partials.subnav')
    </div>

    <main class="page">
        @if(session('success')) <div class="alert">{{ session('success') }}</div> @endif
        @if($errors->any())
            <div class="err">@foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach</div>
        @endif

        @yield('content')
    </main>

    {{-- ===== MODAL PROFIL ADMIN ===== --}}
    <div class="mprofil" id="modalProfil" onclick="if(event.target===this)tutupModalProfil()">
        <div class="mprofil-box" role="dialog" aria-modal="true" aria-labelledby="judulProfil">
            <h3 id="judulProfil">Profil Admin</h3>
            <p class="mp-sub">Kelola informasi akun admin kamu</p>

            @if (session('success_profil'))
                <div class="mp-ok">{{ session('success_profil') }}</div>
            @endif

            @if ($errors->profilForm->any())
                <div class="mp-err">
                    <strong>Ada kesalahan input:</strong>
                    <ul>
                        @foreach ($errors->profilForm->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mp-fotowrap">
                    <div class="mp-fotoarea">
                        <label for="inputFotoProfil" class="mp-foto" id="previewFotoProfilWrapper">
                            @if ($adminProfil && $adminProfil->foto)
                                <img src="{{ asset('image/' . $adminProfil->foto) }}" alt="Foto profil">
                            @else
                                Klik untuk upload foto
                            @endif
                        </label>
                        <button type="button" class="mp-hapus" id="btnHapusFoto" onclick="hapusFotoProfil()" title="Hapus foto profil"
                            style="{{ ($adminProfil && $adminProfil->foto) ? '' : 'display:none;' }}">&times;</button>
                    </div>
                    <div class="mp-hint">Klik lingkaran untuk mengganti foto</div>

                    <input type="file" name="foto" id="inputFotoProfil" accept="image/*" style="display:none" onchange="previewFotoProfil(this)">
                    <input type="hidden" name="hapus_foto" id="hapusFotoFlag" value="0">
                </div>

                <div class="fg">
                    <label class="lb">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $adminProfil->nama_lengkap ?? '') }}" placeholder="Nama lengkap admin">
                </div>

                <div class="fg">
                    <label class="lb">Username</label>
                    <input type="text" name="username" value="{{ old('username', $adminProfil->username ?? '') }}" placeholder="Username login">
                </div>

                <div class="fg">
                    <label class="lb">Password Baru</label>
                    <div class="mp-passwrap">
                        <input type="text" name="password" id="passwordProfil" style="-webkit-text-security:disc;"
                            placeholder="Kosongkan jika tidak diganti" autocomplete="new-password"
                            data-lpignore="true" data-1p-ignore data-form-type="other">
                        <button type="button" class="mp-eye" id="eyeProfil" onclick="togglePasswordProfil()">Lihat</button>
                    </div>
                </div>

                <div class="mp-actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-outline" onclick="tutupModalProfil()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL KONFIRMASI LOGOUT ===== --}}
    <div class="mlogout" id="modalLogout" onclick="if(event.target===this)tutupModalLogout()">
        <div class="mlogout-box" role="dialog" aria-modal="true" aria-labelledby="judulLogout">
            <div class="mlogout-ic">⎋</div>
            <h3 id="judulLogout">Konfirmasi Logout</h3>
            <p>Yakin ingin logout dari panel admin?</p>
            <div class="actions">
                <a href="{{ route('admin.logout') }}" class="btn btn-primary">Ya, Logout</a>
                <button type="button" class="btn btn-outline" onclick="tutupModalLogout()">Batal</button>
            </div>
        </div>
    </div>

    <script>
        function bukaModalLogout()  { document.getElementById('modalLogout').classList.add('open'); }
        function tutupModalLogout() { document.getElementById('modalLogout').classList.remove('open'); }
        function bukaModalProfil()  { document.getElementById('modalProfil').classList.add('open'); }
        function tutupModalProfil() { document.getElementById('modalProfil').classList.remove('open'); }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { tutupModalLogout(); tutupModalProfil(); }
        });

        /* ===== Profil admin: pratinjau, hapus foto, lihat password ===== */
        function previewFotoProfil(input) {
            if (!input.files || !input.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewFotoProfilWrapper').innerHTML = '<img src="' + e.target.result + '" alt="Foto profil">';
            };
            reader.readAsDataURL(input.files[0]);

            // Foto baru dipilih -> batalkan penanda hapus, tampilkan lagi tombol X
            document.getElementById('hapusFotoFlag').value = '0';
            document.getElementById('btnHapusFoto').style.display = 'flex';
        }

        function hapusFotoProfil() {
            document.getElementById('inputFotoProfil').value = '';
            document.getElementById('previewFotoProfilWrapper').innerHTML = 'Klik untuk upload foto';
            // Tandai: foto beneran dihapus saat "Simpan Perubahan" diklik
            document.getElementById('hapusFotoFlag').value = '1';
            document.getElementById('btnHapusFoto').style.display = 'none';
        }

        function togglePasswordProfil() {
            var input = document.getElementById('passwordProfil');
            var tombol = document.getElementById('eyeProfil');
            var tersembunyi = input.style.webkitTextSecurity !== 'none';
            input.style.webkitTextSecurity = tersembunyi ? 'none' : 'disc';
            tombol.textContent = tersembunyi ? 'Sembunyikan' : 'Lihat';
        }

        /* Pratinjau foto yang baru dipilih di dalam kotak foto (dipakai halaman konten) */
        function previewFoto(input, boxId) {
            var box = document.getElementById(boxId);
            if (!input.files || !input.files[0] || !box) return;
            var r = new FileReader();
            r.onload = function (e) {
                box.style.backgroundImage = "url('" + e.target.result + "')";
                box.classList.add('has-img');
                box.classList.remove('ph');
            };
            r.readAsDataURL(input.files[0]);
        }

        @if (session('success_profil') || $errors->profilForm->any())
            document.addEventListener('DOMContentLoaded', function () { bukaModalProfil(); });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
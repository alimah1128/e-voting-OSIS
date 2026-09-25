<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Voting OSIS SMK Pertiwi Kuningan</title>

    <!-- Google Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #F3F8FC;
            color: #1E293B;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .navbar {
            background-color: #FFFFFF;
            padding: 14px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #E2E8F0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand img {
            width: 44px;
            height: auto;
            flex-shrink: 0;
        }

        .brand-text h2 {
            font-size: 15px;
            font-weight: 800;
            color: #025088;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .brand-text p {
            font-size: 11px;
            color: #00A3FF;
            font-weight: 600;
        }

        /* Pemisah baris judul: hanya aktif di layar HP */
        .br-mobile {
            display: none;
        }

        /* Wrapper kanan navbar: profil di atas, logout menempel di bawahnya */
        .navbar-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 10px;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .user-profile:hover {
            background: #F0F9FF;
        }

        .user-info {
            text-align: right;
        }

        .user-info .name {
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
        }

        .user-info .class {
            font-size: 11px;
            color: #94A3B8;
        }

        .avatar {
            width: 36px;
            height: 36px;
            background-color: #00A3FF;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            color: #DC2626;
            padding: 3px 10px 3px 0;
            transition: color 0.2s ease;
        }

        .btn-logout:hover {
            color: #B91C1C;
            text-decoration: underline;
        }

        /* ====== MODAL KONFIRMASI LOGOUT ====== */
        .modal-overlay-logout {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45);
            display: none; align-items: center; justify-content: center;
            z-index: 999; padding: 20px;
        }
        .modal-overlay-logout.active { display: flex; }
        .modal-logout-box {
            background: #fff; border-radius: 20px; width: 100%; max-width: 360px;
            padding: 32px 28px; text-align: center;
        }
        .modal-logout-icon {
            width: 56px; height: 56px; background: #E0F2FE; color: #00A3FF; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 22px;
        }
        .modal-logout-box h3 { font-size: 17px; font-weight: 800; color: #0F172A; margin-bottom: 6px; }
        .modal-logout-box p { font-size: 12.5px; color: #64748B; margin-bottom: 22px; }
        .modal-logout-actions { display: flex; gap: 10px; }
        .btn-logout-confirm {
            flex: 1; background: #00A3FF; color: #fff; border: none; padding: 11px 0;
            border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer; text-decoration: none;
            display: flex; align-items: center; justify-content: center;
        }
        .btn-logout-confirm:hover { background: #0082CC; }
        .btn-logout-cancel {
            flex: 1; background: #fff; color: #64748B; border: 1px solid #E2E8F0; padding: 11px 0;
            border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer;
        }

        /* Container Content */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px 20px 40px;
            width: 100%;
            flex: 1;
        }

        /* Banner Welcome - Biru Cerah Sesuai Figma */
        .banner {
            background: linear-gradient(90deg, #00A3FF, #0082CC);
            border-radius: 16px;
            padding: 28px 32px;
            color: white;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(0, 163, 255, 0.2);
        }

        .banner p.welcome-text {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 2px;
        }

        .banner h1 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .banner p.sub-text {
            font-size: 12px;
            opacity: 0.85;
        }

        /* Grid Stats 3 Kolom */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            cursor: pointer;
        }

        /* Laptop/PC: kartu naik saat kursor diarahkan */
        @media (hover: hover) {
            .stat-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 24px rgba(0, 163, 255, 0.15);
                border-color: #BAE6FD;
            }
        }

        /* HP (tanpa kursor): efek yang sama, aktif saat kartu berada di tengah layar
           ketika di-scroll (kelas .in-view diatur oleh script di bawah) atau saat disentuh */
        @media (hover: none) {
            .stat-card.in-view,
            .stat-card:active {
                transform: translateY(-6px);
                box-shadow: 0 12px 24px rgba(0, 163, 255, 0.15);
                border-color: #BAE6FD;
            }
        }

        /* Hormati pengguna yang mematikan animasi di perangkatnya */
        @media (prefers-reduced-motion: reduce) {
            .stat-card { transition: none; }
            .stat-card:hover,
            .stat-card.in-view,
            .stat-card:active { transform: none; }
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background-color: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #00A3FF;
            flex-shrink: 0;
        }

        .stat-content label {
            font-size: 11px;
            color: #94A3B8;
            font-weight: 500;
            display: block;
            margin-bottom: 2px;
        }

        .stat-content h3 {
            font-size: 15px;
            font-weight: 700;
            color: #0F172A;
            line-height: 1.2;
        }

        .stat-content p {
            font-size: 11px;
            color: #94A3B8;
            margin-top: 2px;
        }

        /* Badge Countdown */
        .badge-timer {
            display: inline-block;
            background-color: #E0F2FE;
            color: #00A3FF;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            margin-top: 6px;
        }

        /* Call To Action Box */
        .cta-card {
            background: white;
            border-radius: 16px;
            padding: 40px 20px;
            border: 1px solid #E2E8F0;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .cta-icon-box {
            width: 56px;
            height: 56px;
            background-color: #E0F2FE;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: #00A3FF;
            font-size: 22px;
        }

        .cta-card h2 {
            font-size: 20px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 8px;
        }

        .cta-card p {
            font-size: 13px;
            color: #64748B;
            max-width: 500px;
            margin: 0 auto 24px;
            line-height: 1.6;
        }

        .btn-kandidat {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #00A3FF;
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 12px rgba(0, 163, 255, 0.3);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-kandidat:hover {
            background: #0082CC;
            transform: translateY(-1px);
        }

        /* Footer Tagline */
        .footer-tagline {
            text-align: center;
            font-size: 12px;
            font-style: italic;
            color: #00A3FF;
            margin-top: 25px;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .navbar {
                padding: 14px 20px;
            }
        }

        @media (max-width: 480px) {
            .navbar { padding: 10px 14px; gap: 8px; }
            .brand { gap: 8px; min-width: 0; }
            .brand img { width: 40px; }
            .brand-text h2 { font-size: 11px; line-height: 1.25; }
            .brand-text p { font-size: 9px; }
            /* Di HP: "OSIS SMK" di baris pertama, "PERTIWI KUNINGAN" di baris kedua */
            .br-mobile { display: block; }
            .user-profile { gap: 8px; padding: 4px 6px; }
            .user-info .name { font-size: 11.5px; line-height: 1.25; }
            .user-info .class { font-size: 10px; }
            .avatar { width: 32px; height: 32px; font-size: 13px; }
            .btn-logout { font-size: 10.5px; }
            .container {
                padding: 20px 14px 30px;
            }
            .banner {
                padding: 20px;
            }
            .banner h1 {
                font-size: 20px;
            }
            .cta-card {
                padding: 28px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar">
        <div class="brand">
            <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
            <div class="brand-text">
                <h2>OSIS SMK <br class="br-mobile">Pertiwi Kuningan</h2>
                <p>E-Voting OSIS {{ $tahunAjaran }}</p>
            </div>
        </div>

        <div class="navbar-right">
            <a href="{{ route('siswa.profil') }}" class="user-profile" style="text-decoration:none;">
                <div class="user-info">
                    <div class="name">{{ $siswaAktif->nama }}</div>
                    <div class="class">{{ $siswaAktif->kelas }}</div>
                </div>
                <div class="avatar">
                    @if ($siswaAktif->foto)
                        <img src="{{ asset('image/' . $siswaAktif->foto) }}" alt="Foto">
                    @else
                        {{ strtoupper(substr($siswaAktif->nama, 0, 1)) }}
                    @endif
                </div>
            </a>

            <a href="#" class="btn-logout" onclick="bukaModalLogout(event)">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </a>
        </div>
    </nav>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal-overlay-logout" id="modalLogout">
        <div class="modal-logout-box">
            <div class="modal-logout-icon">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </div>
            <h3>Konfirmasi Logout</h3>
            <p>Yakin ingin keluar dari akun ini?</p>
            <div class="modal-logout-actions">
                <a href="{{ route('logout') }}" class="btn-logout-confirm">Ya, Logout</a>
                <button type="button" class="btn-logout-cancel" onclick="tutupModalLogout()">Batal</button>
            </div>
        </div>
    </div>

    <script>
        function bukaModalLogout(e) {
            e.preventDefault();
            document.getElementById('modalLogout').classList.add('active');
        }
        function tutupModalLogout() {
            document.getElementById('modalLogout').classList.remove('active');
        }
    </script>

    <!-- Main Content -->
    <div class="container">

        <!-- Banner Sapaan Biru Cerah -->
        <div class="banner">
            <p class="welcome-text">Selamat datang,</p>
            <h1>{{ $siswaAktif->nama }}</h1>
            <p class="sub-text">{{ $siswaAktif->kelas }} · Tahun Ajaran {{ $tahunAjaran }}</p>
        </div>

        <!-- Section Ringkasan Status -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-content">
                    <label>Total Kandidat</label>
                    <h3>{{ $jumlahKandidat }} Pasangan</h3>
                    <p>Pilih satu pasangan</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
                <div class="stat-content">
                    <label>Status Voting</label>
                    <h3>{{ $statusVotingLabel }}</h3>
                    <p>{{ $statusVotingSub }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stat-content">
                    <label>Batas Waktu Voting</label>
                    <h3>{{ $periodeTeks }}</h3>
                    <p>{{ $periodeSub }}</p>
                    @if ($sisaWaktuTeks)
                        <div class="badge-timer">
                            <i class="fa-regular fa-clock"></i> {{ $sisaWaktuTeks }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Box Aksi Pemilihan -->
        <div class="cta-card">
            <div class="cta-icon-box">
                <i class="fa-solid fa-box-archive"></i>
            </div>
            <h2>Gunakan Hak Pilihmu!</h2>
            <p>Setiap suara berarti bagi kemajuan OSIS. Pelajari profil kandidat dan pilih pemimpin terbaik untuk SMK Pertiwi Kuningan.</p>

            <!-- Link BISA DIKLIK -->
            <a href="/kandidat" class="btn-kandidat">
                Lihat Daftar Kandidat <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>

        <div class="footer-tagline">
            "SUARAMU. MASA DEPAN SEKOLAH KITA."
        </div>

    </div>

    <!-- Efek kartu ikut bergerak saat di-scroll (khusus HP / layar sentuh) -->
    <script>
        (function () {
            // Laptop/PC sudah memakai :hover, jadi script ini tidak perlu jalan
            if (window.matchMedia('(hover: hover)').matches) return;

            var cards = document.querySelectorAll('.stat-card');
            if (!cards.length) return;

            var ticking = false;

            function update() {
                var tengahLayar = window.innerHeight / 2;
                var terdekat = null;
                var jarakTerdekat = Infinity;

                // Cari kartu yang posisinya paling dekat dengan tengah layar
                cards.forEach(function (card) {
                    var r = card.getBoundingClientRect();
                    var jarak = Math.abs((r.top + r.height / 2) - tengahLayar);
                    if (jarak < jarakTerdekat) {
                        jarakTerdekat = jarak;
                        terdekat = card;
                    }
                });

                // Hanya satu kartu yang aktif, dan hanya kalau sudah cukup dekat ke tengah layar
                var batas = window.innerHeight * 0.35;
                cards.forEach(function (card) {
                    card.classList.toggle('in-view', card === terdekat && jarakTerdekat < batas);
                });

                ticking = false;
            }

            function onScroll() {
                if (!ticking) {
                    ticking = true;
                    window.requestAnimationFrame(update);
                }
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll);
            update();
        })();
    </script>

</body>
</html>
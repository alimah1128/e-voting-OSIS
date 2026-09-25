<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kandidat - E-Voting OSIS SMK Pertiwi Kuningan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #F3F8FC; color: #1E293B; min-height: 100vh; }

        .navbar {
            background-color: #FFFFFF;
            padding: 14px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #E2E8F0;
        }
        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .brand img { width: 44px; height: auto; flex-shrink: 0; }
        .brand-text h2 { font-size: 15px; font-weight: 800; color: #025088; line-height: 1.2; text-transform: uppercase; }
        .brand-text p { font-size: 11px; color: #00A3FF; font-weight: 600; }

        /* Pemisah baris judul: hanya aktif di layar HP */
        .br-mobile { display: none; }

        /* Wrapper kanan navbar: profil di atas, logout menempel di bawahnya */
        .navbar-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
        }

        .user-profile {
            display: flex; align-items: center; gap: 12px; text-decoration: none;
            padding: 6px 10px; border-radius: 10px; transition: background 0.2s ease;
        }
        .user-profile:hover { background: #F0F9FF; }
        .user-info { text-align: right; }
        .user-info .name { font-size: 13px; font-weight: 700; color: #1E293B; }
        .user-info .class { font-size: 11px; color: #94A3B8; }
        .avatar {
            width: 36px; height: 36px; background-color: #00A3FF; color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
            overflow: hidden; flex-shrink: 0;
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }

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

        .page-header { text-align: center; padding: 30px 20px 20px; }
        .page-header h1 { font-size: 24px; font-weight: 800; color: #0F172A; margin-bottom: 6px; }
        .page-header p { font-size: 13px; color: #64748B; line-height: 1.5; }

        .container { max-width: 1200px; margin: 0 auto; padding: 10px 20px 50px; }
        .candidates-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }

        .candidate-card {
            background: #FFFFFF; border-radius: 16px; border: 1px solid #E2E8F0;
            overflow: hidden; display: flex; flex-direction: column;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        /* Laptop/PC: kartu naik & foto membesar saat kursor diarahkan */
        @media (hover: hover) {
            .candidate-card:hover {
                box-shadow: 0 14px 32px rgba(0, 0, 0, 0.1);
                transform: translateY(-6px);
            }
            .candidate-card:hover .photo-box img { transform: scale(1.05); }
        }

        /* HP (tanpa kursor): efek yang sama, aktif saat kartu sedang berada di tengah layar
           ketika di-scroll (kelas .in-view diatur oleh script di bawah) */
        @media (hover: none) {
            .candidate-card.in-view {
                box-shadow: 0 14px 32px rgba(0, 0, 0, 0.1);
                transform: translateY(-6px);
            }
            .candidate-card.in-view .photo-box img { transform: scale(1.05); }
        }

        .card-header { background: #00A3FF; color: white; padding: 14px 20px; display: flex; align-items: center; gap: 12px; }
        .number-badge {
            width: 32px; height: 32px; background-color: white; color: #00A3FF;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px;
        }
        .card-header-text label { font-size: 10px; opacity: 0.85; display: block; line-height: 1; }
        .card-header-text h3 { font-size: 14px; font-weight: 700; }

        .card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .photo-box { width: 100%; height: 220px; border-radius: 12px; overflow: hidden; background-color: #E2E8F0; margin-bottom: 16px; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }

        .pasangan-names {
            display: flex; align-items: center; justify-content: space-around; text-align: center;
            margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #F1F5F9;
        }
        .person-info span.role-tag {
            font-size: 9px; background-color: #E0F2FE; color: #00A3FF; padding: 2px 8px;
            border-radius: 10px; font-weight: 600; display: inline-block; margin-bottom: 4px;
        }
        .person-info h4 { font-size: 13px; font-weight: 700; color: #0F172A; }
        .person-info p { font-size: 11px; color: #94A3B8; }
        .ampersand { color: #CBD5E1; font-weight: 700; font-size: 14px; }

        .detail-section { margin-bottom: 16px; }
        .detail-title {
            font-size: 11px; font-weight: 800; color: #00A3FF; text-transform: uppercase;
            margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
        }
        .detail-title::before { content: ''; display: inline-block; width: 3px; height: 12px; background-color: #00A3FF; border-radius: 2px; }
        .detail-text { font-size: 11.5px; color: #475569; line-height: 1.5; }
        .misi-list { padding-left: 16px; font-size: 11.5px; color: #475569; line-height: 1.5; }
        .misi-list li { margin-bottom: 4px; }

        .prokerja-wrapper { display: flex; flex-direction: column; gap: 6px; margin-top: 6px; }
        .prokerja-badge {
            background-color: #F0F9FF; border: 1px solid #BAE6FD; color: #0284C7;
            font-size: 11px; padding: 6px 12px; border-radius: 20px; display: flex;
            align-items: center; gap: 6px; font-weight: 500;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }
        @media (hover: hover) {
            .prokerja-badge:hover {
                background-color: #E0F2FE;
                box-shadow: 0 2px 6px rgba(0, 163, 255, 0.15);
            }
        }
        .prokerja-badge::before { content: '•'; font-weight: bold; }

        .btn-pilih {
            width: 100%; padding: 12px; background-color: #00A3FF; color: white; border: none;
            border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer; margin-top: auto;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            box-shadow: 0 4px 10px rgba(0, 163, 255, 0.25); transition: all 0.2s; text-decoration: none;
        }
        @media (hover: hover) {
            .btn-pilih:hover {
                background-color: #0082CC;
                transform: translateY(-2px);
                box-shadow: 0 8px 18px rgba(0, 163, 255, 0.35);
            }
        }
        .btn-pilih:active {
            transform: translateY(0) scale(0.97);
            box-shadow: 0 3px 8px rgba(0, 163, 255, 0.3);
        }
        .btn-pilih.disabled { background-color: #CBD5E1; box-shadow: none; cursor: not-allowed; pointer-events: none; }

        .empty-state {
            background: #fff; border: 1px solid #E2E8F0; border-radius: 16px;
            padding: 50px 20px; text-align: center; color: #64748B; font-size: 13px;
        }

        .info-banner {
            max-width: 1200px; margin: 0 auto 20px; padding: 14px 24px;
            background: #F0FDF4; border: 1px solid #BBF7D0; color: #16A34A;
            border-radius: 12px; font-size: 13px; font-weight: 600;
        }

        /* Hormati pengguna yang mematikan animasi di perangkatnya */
        @media (prefers-reduced-motion: reduce) {
            .candidate-card,
            .photo-box img { transition: none; }
            .candidate-card:hover,
            .candidate-card.in-view { transform: none; }
            .candidate-card:hover .photo-box img,
            .candidate-card.in-view .photo-box img { transform: none; }
        }

        @media (max-width: 992px) { .candidates-grid { grid-template-columns: 1fr; } }

        @media (max-width: 480px) {
            .navbar { padding: 10px 14px; gap: 8px; }
            .brand { gap: 8px; min-width: 0; }
            .brand img { width: 40px; }
            .brand-text h2 { font-size: 11px; line-height: 1.25; }
            .brand-text p { font-size: 9px; }
            .br-mobile { display: block; }
            .user-profile { gap: 8px; padding: 4px 6px; }
            .user-info .name { font-size: 11.5px; line-height: 1.25; }
            .user-info .class { font-size: 10px; }
            .avatar { width: 32px; height: 32px; font-size: 13px; }
            .btn-logout { font-size: 10.5px; }
            .page-header { padding: 20px 16px 14px; }
            .page-header h1 { font-size: 19px; }
            .container { padding: 6px 14px 40px; }
            .photo-box { height: 180px; }
            .card-body { padding: 16px; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="/dashboard" class="brand">
            <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
            <div class="brand-text">
                <h2>OSIS SMK <br class="br-mobile">Pertiwi Kuningan</h2>
                <p>E-Voting OSIS {{ $tahunAjaran }}</p>
            </div>
        </a>

        <div class="navbar-right">
            <a href="{{ route('siswa.profil') }}" class="user-profile">
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

    <div class="page-header">
        <h1>Daftar Pasangan Kandidat</h1>
        <p>Baca profil kandidat dengan seksama sebelum menentukan pilihan.<br>Suara bersifat rahasia dan tidak dapat diubah.</p>
    </div>

    <div class="container">
        @if ($sudahVoting)
            <div class="info-banner">
                <i class="fa-solid fa-circle-check"></i> Kamu sudah melakukan voting untuk periode ini. Terima kasih!
            </div>
        @endif

        @if (! $periodeAktif)
            <div class="empty-state">Belum ada periode voting yang sedang berlangsung saat ini.</div>
        @elseif ($kandidats->isEmpty())
            <div class="empty-state">Belum ada kandidat yang terdaftar untuk periode ini.</div>
        @else
            <div class="candidates-grid">
                @foreach ($kandidats as $kandidat)
                    <div class="candidate-card">
                        <div class="card-header">
                            <div class="number-badge">{{ $kandidat->no_urut }}</div>
                            <div class="card-header-text">
                                <label>Pasangan Calon</label>
                                <h3>Nomor Urut {{ $kandidat->no_urut }}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="photo-box">
                                @if ($kandidat->foto)
                                    <img src="{{ asset('image/' . $kandidat->foto) }}" alt="Kandidat {{ $kandidat->no_urut }}">
                                @endif
                            </div>
                            <div class="pasangan-names">
                                <div class="person-info">
                                    <span class="role-tag">Calon Ketua</span>
                                    <h4>{{ $kandidat->nama_ketua }}</h4>
                                    <p>{{ $kandidat->kelas_ketua }}</p>
                                </div>
                                <div class="ampersand">&</div>
                                <div class="person-info">
                                    <span class="role-tag">Calon Wakil</span>
                                    <h4>{{ $kandidat->nama_wakil }}</h4>
                                    <p>{{ $kandidat->kelas_wakil }}</p>
                                </div>
                            </div>

                            <div class="detail-section">
                                <div class="detail-title">VISI</div>
                                <p class="detail-text">{{ $kandidat->visi }}</p>
                            </div>

                            @if (count($kandidat->misi) > 0)
                                <div class="detail-section">
                                    <div class="detail-title">MISI</div>
                                    <ul class="misi-list">
                                        @foreach ($kandidat->misi as $poin)
                                            <li>{{ $poin }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (count($kandidat->program_kerja) > 0)
                                <div class="detail-section" style="margin-bottom: 25px;">
                                    <div class="detail-title">PROGRAM KERJA UNGGULAN</div>
                                    <div class="prokerja-wrapper">
                                        @foreach ($kandidat->program_kerja as $poin)
                                            <div class="prokerja-badge">{{ $poin }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <a href="{{ $sudahVoting ? '#' : '/konfirmasi/' . $kandidat->id }}" class="btn-pilih {{ $sudahVoting ? 'disabled' : '' }}">
                                <i class="fa-solid fa-check"></i> {{ $sudahVoting ? 'Sudah Voting' : 'Pilih Pasangan Ini' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Efek kartu ikut bergerak saat di-scroll (khusus HP / layar sentuh) -->
    <script>
        (function () {
            // Laptop/PC sudah memakai :hover, jadi script ini tidak perlu jalan
            if (window.matchMedia('(hover: hover)').matches) return;

            var cards = document.querySelectorAll('.candidate-card');
            if (!cards.length) return;

            var ticking = false;

            function update() {
                var tengahLayar = window.innerHeight / 2;
                var jarakList = [];
                var jarakTerdekat = Infinity;

                // Kartu kandidat itu tinggi, jadi yang dihitung adalah apakah kartu
                // sedang "melewati" garis tengah layar (jarak 0), bukan titik tengah kartunya
                cards.forEach(function (card) {
                    var r = card.getBoundingClientRect();
                    var jarak = 0;
                    if (r.top > tengahLayar) jarak = r.top - tengahLayar;
                    else if (r.bottom < tengahLayar) jarak = tengahLayar - r.bottom;
                    jarakList.push(jarak);
                    if (jarak < jarakTerdekat) jarakTerdekat = jarak;
                });

                // Hanya kartu yang paling dekat ke tengah layar yang aktif,
                // dan hanya kalau jaraknya masih wajar
                var batas = window.innerHeight * 0.25;
                cards.forEach(function (card, i) {
                    var aktif = jarakList[i] <= jarakTerdekat + 1 && jarakTerdekat < batas;
                    card.classList.toggle('in-view', aktif);
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
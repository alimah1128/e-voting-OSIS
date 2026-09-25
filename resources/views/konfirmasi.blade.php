<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pilihan - E-Voting OSIS SMK Pertiwi Kuningan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #F3F8FC; color: #1E293B; min-height: 100vh; display: flex; flex-direction: column; }

        .navbar {
            background-color: #FFFFFF; padding: 14px 40px; display: flex;
            justify-content: space-between; align-items: center; border-bottom: 1px solid #E2E8F0;
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

        .user-profile { display: flex; align-items: center; gap: 12px; text-decoration: none; padding: 6px 10px; border-radius: 10px; transition: background 0.2s ease; }
        .user-profile:hover { background: #F0F9FF; }
        .user-info { text-align: right; }
        .user-info .name { font-size: 13px; font-weight: 700; color: #1E293B; }
        .user-info .class { font-size: 11px; color: #94A3B8; }
        .avatar {
            width: 36px; height: 36px; background-color: #00A3FF; color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;
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

        .container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px; }
        .confirm-card {
            background: #FFFFFF; width: 100%; max-width: 520px; border-radius: 20px; padding: 32px 28px;
            text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04); border: 1px solid #E2E8F0;
        }
        .warning-icon {
            width: 48px; height: 48px; background-color: #FEF3C7; color: #D97706; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 20px;
        }
        .confirm-card h2 { font-size: 20px; font-weight: 800; color: #0F172A; margin-bottom: 6px; }
        .confirm-card p.sub-title { font-size: 12px; color: #64748B; margin-bottom: 24px; line-height: 1.5; }

        .preview-box {
            background-color: #F0F9FF; border: 1.5px solid #BAE6FD; border-radius: 16px;
            padding: 20px; margin-bottom: 24px; text-align: left;
        }
        .preview-header { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .number-badge {
            width: 32px; height: 32px; background-color: #00A3FF; color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
        }
        .preview-header-text label { font-size: 10px; color: #00A3FF; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: block; }
        .preview-header-text h3 { font-size: 15px; font-weight: 800; color: #0284C7; }
        .photo-box { width: 100%; height: 220px; border-radius: 12px; overflow: hidden; background-color: #E2E8F0; margin-bottom: 16px; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .pasangan-names { display: flex; align-items: center; justify-content: space-around; text-align: center; }
        .person-info span { font-size: 10px; color: #00A3FF; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .person-info h4 { font-size: 13px; font-weight: 700; color: #0F172A; }
        .person-info p { font-size: 11px; color: #94A3B8; }
        .ampersand { color: #00A3FF; font-weight: 800; font-size: 15px; }

        .question-text { font-size: 13px; font-weight: 700; color: #1E293B; margin-bottom: 20px; }
        .action-buttons { display: flex; gap: 12px; }
        .btn-batal {
            flex: 1; padding: 12px; background-color: #FFFFFF; border: 1px solid #CBD5E1; color: #475569;
            border-radius: 10px; font-weight: 700; font-size: 13px; text-decoration: none;
            display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        }
        .btn-batal:hover { background-color: #F8FAFC; }
        .form-confirm { flex: 1.2; display: flex; }
        .btn-konfirmasi {
            width: 100%; padding: 12px; background-color: #00A3FF; border: none; color: white;
            border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 163, 255, 0.3); transition: all 0.2s;
        }
        .btn-konfirmasi:hover { background-color: #0082CC; }

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
            .container { padding: 24px 14px; }
            .confirm-card { padding: 24px 18px; }
            .action-buttons { flex-direction: column; }
            .form-confirm { width: 100%; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="brand">
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

    <div class="container">
        <div class="confirm-card">

            <div class="warning-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <h2>Konfirmasi Pilihan Anda</h2>
            <p class="sub-title">Pastikan pilihan Anda sudah benar. Suara tidak dapat diubah setelah dikonfirmasi.</p>

            <div class="preview-box">
                <div class="preview-header">
                    <div class="number-badge">{{ $kandidat->no_urut }}</div>
                    <div class="preview-header-text">
                        <label>Anda Memilih</label>
                        <h3>Pasangan Nomor Urut {{ $kandidat->no_urut }}</h3>
                    </div>
                </div>

                <div class="photo-box">
                    @if ($kandidat->foto)
                        <img src="{{ asset('image/' . $kandidat->foto) }}" alt="Foto Kandidat {{ $kandidat->no_urut }}">
                    @endif
                </div>

                <div class="pasangan-names">
                    <div class="person-info">
                        <span>Ketua</span>
                        <h4>{{ $kandidat->nama_ketua }}</h4>
                        <p>{{ $kandidat->kelas_ketua }}</p>
                    </div>
                    <div class="ampersand">&</div>
                    <div class="person-info">
                        <span>Wakil</span>
                        <h4>{{ $kandidat->nama_wakil }}</h4>
                        <p>{{ $kandidat->kelas_wakil }}</p>
                    </div>
                </div>
            </div>

            <p class="question-text">Apakah Anda yakin dengan pilihan ini?</p>

            <div class="action-buttons">
                <a href="{{ route('kandidat') }}" class="btn-batal">Batal</a>

                <form action="{{ route('submit.vote') }}" method="POST" class="form-confirm">
                    @csrf
                    <input type="hidden" name="kandidat_id" value="{{ $kandidat->id }}">
                    <button type="submit" class="btn-konfirmasi">
                        Ya, Konfirmasi
                    </button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>
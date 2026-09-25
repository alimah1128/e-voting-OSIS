<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - E-Voting OSIS SMK Pertiwi Kuningan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #F3F8FC; color: #1E293B; min-height: 100vh; }

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

        .user-profile {
            display: flex; align-items: center; gap: 12px; text-decoration: none;
            padding: 6px 10px; border-radius: 10px; transition: background 0.2s ease;
        }
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

        .container { max-width: 480px; margin: 0 auto; padding: 40px 20px; }

        a.btn-back {
            display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;
            color: #00A3FF; font-size: 13px; font-weight: 700; text-decoration: none;
            padding: 9px 16px; border-radius: 10px;
            background: #FFFFFF; border: 1px solid #E2E8F0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }
        a.btn-back:hover {
            background: #F0F9FF;
            box-shadow: 0 4px 12px rgba(0, 163, 255, 0.15);
            transform: translateX(-2px);
        }
        a.btn-back:active { transform: translateX(-2px) scale(0.97); }

        .edit-card {
            background: #fff; border-radius: 20px; border: 1px solid #E2E8F0;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06); padding: 30px 28px;
        }
        .edit-card h2 { font-size: 17px; font-weight: 800; color: #0F172A; margin-bottom: 22px; text-align: center; }

        .error-box {
            background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626;
            border-radius: 10px; padding: 12px 14px; font-size: 12px; margin-bottom: 18px;
        }
        .error-box ul { padding-left: 18px; }

        .foto-preview-wrapper { text-align: center; margin-bottom: 22px; }
        .foto-preview {
            width: 96px; height: 96px; border-radius: 50%; overflow: hidden;
            background: #00A3FF; color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 34px; margin: 0 auto 12px;
            border: 3px solid #E2E8F0;
        }
        .foto-preview img { width: 100%; height: 100%; object-fit: cover; }

        label.field-label {
            font-size: 11px; font-weight: 700; color: #64748B; letter-spacing: 0.4px;
            display: block; margin-bottom: 6px; text-transform: uppercase;
        }
        .field-group { margin-bottom: 18px; }
        input[type="password"] {
            width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid #E2E8F0;
            background-color: #F8FAFC; font-size: 13px; color: #334155; outline: none;
            transition: border-color 0.2s ease, background 0.2s ease;
            font-family: 'Poppins', sans-serif;
        }
        input[type="password"]:focus {
            border-color: #38BDF8; background-color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }
        .hint { font-size: 10.5px; color: #94A3B8; margin-top: 4px; }

        /* Custom file upload */
        input[type="file"] { display: none; }

        .file-upload-wrapper {
            display: flex; align-items: center; gap: 12px;
            border: 1px dashed #BAE6FD; background-color: #F8FAFC;
            border-radius: 10px; padding: 10px 14px; cursor: pointer;
            transition: border-color 0.2s ease, background 0.2s ease;
        }
        .file-upload-wrapper:hover { border-color: #38BDF8; background-color: #F0F9FF; }

        .btn-choose-file {
            display: inline-flex; align-items: center; gap: 6px;
            background: #00A3FF; color: #fff; border: none;
            padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 700;
            cursor: pointer; white-space: nowrap;
            transition: background 0.2s ease, transform 0.15s ease;
        }
        .btn-choose-file:hover { background: #0082CC; }
        .btn-choose-file:active { transform: scale(0.96); }

        .file-name-text { font-size: 12px; color: #64748B; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .btn-simpan {
            width: 100%; padding: 12px; background-color: #00A3FF; color: white; border: none;
            border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            box-shadow: 0 4px 10px rgba(0, 163, 255, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            margin-top: 6px;
        }
        .btn-simpan:hover { background-color: #0082CC; transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0, 163, 255, 0.35); }
        .btn-simpan:active { transform: translateY(0) scale(0.97); }

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
            .edit-card { padding: 24px 18px; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="/dashboard" class="brand">
            <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
            <div class="brand-text">
                <h2>OSIS SMK <br class="br-mobile">Pertiwi Kuningan</h2>
                <p>E-Voting OSIS 2025/2026</p>
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
        <a href="{{ route('siswa.profil') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Profil</a>

        <div class="edit-card">
            <h2>Edit Profil</h2>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('siswa.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="foto-preview-wrapper">
                    <div class="foto-preview" id="fotoPreview">
                        @if ($siswa->foto)
                            <img src="{{ asset('image/' . $siswa->foto) }}" alt="Foto Profil" id="previewImg">
                        @else
                            <span id="previewInitial">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</span>
                            <img src="" alt="" id="previewImg" style="display:none;">
                        @endif
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Ganti Foto Profil</label>
                    <label for="fotoInput" class="file-upload-wrapper">
                        <span class="btn-choose-file"><i class="fa-solid fa-upload"></i> Pilih File</span>
                        <span class="file-name-text" id="fileNameText">Tidak ada file yang dipilih</span>
                    </label>
                    <input type="file" id="fotoInput" name="foto" accept="image/png, image/jpeg" onchange="previewFoto(event)">
                    <div class="hint">Format JPG/PNG, maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</div>
                </div>

                <div class="field-group">
                    <label class="field-label">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                </div>

                <div class="field-group">
                    <label class="field-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn-simpan">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <script>
        function previewFoto(event) {
            const file = event.target.files[0];
            if (!file) return;

            document.getElementById('fileNameText').textContent = file.name;

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.getElementById('previewImg');
                const initial = document.getElementById('previewInitial');
                img.src = e.target.result;
                img.style.display = 'block';
                if (initial) initial.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
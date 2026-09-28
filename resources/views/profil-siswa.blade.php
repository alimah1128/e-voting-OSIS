<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - E-Voting OSIS SMK Pertiwi Kuningan</title>

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

        .container { max-width: 480px; margin: 0 auto; padding: 36px 20px; }

        .alert-success {
            background: #F0FDF4; border: 1px solid #BBF7D0; color: #16A34A;
            border-radius: 12px; padding: 12px 16px; font-size: 13px; font-weight: 600;
            margin-bottom: 18px;
        }

        a.btn-back-dashboard {
            display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;
            color: #00A3FF; font-size: 13px; font-weight: 700; text-decoration: none;
            padding: 9px 16px; border-radius: 10px;
            background: #FFFFFF; border: 1px solid #E2E8F0;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        a.btn-back-dashboard:hover { background: #F0F9FF; transform: translateX(-2px); }

        /* ====== CARD PROFIL (konsep sederhana seperti Profil Admin) ====== */
        .profile-card {
            background: #fff; border-radius: 18px; border: 1px solid #E2E8F0;
            padding: 28px 26px 30px; text-align: center;
        }

        .avatar-lg {
            width: 76px; height: 76px; border-radius: 50%; margin: 0 auto 14px;
            background: #00A3FF; color: #fff; font-weight: 800; font-size: 28px;
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .avatar-lg img { width: 100%; height: 100%; object-fit: cover; }

        .profile-card h2 { font-size: 17px; font-weight: 800; color: #0F172A; margin-bottom: 6px; }
        .profile-card .sub {
            font-size: 11.5px; color: #64748B; margin-bottom: 18px;
            display: inline-block; background: #F1F5F9; padding: 4px 14px; border-radius: 20px;
        }

        .btn-edit-profil {
            display: inline-flex; align-items: center; gap: 6px;
            background: #00A3FF; color: #fff; border: none; cursor: pointer;
            font-size: 12.5px; font-weight: 700; padding: 9px 20px; border-radius: 24px;
            margin-bottom: 24px;
            transition: transform 0.15s ease;
        }
        .btn-edit-profil:hover { transform: translateY(-1px); }
        .btn-edit-profil:active { transform: translateY(0) scale(0.97); }

        /* ====== FIELD KOTAK BER-IKON (gaya sama seperti modal Profil Admin) ====== */
        .field-group { margin-bottom: 14px; text-align: left; }
        .field-group label {
            font-size: 11.5px; font-weight: 700; color: #64748B; margin-bottom: 6px; display: block;
        }
        .field-display {
            display: flex; align-items: center; gap: 10px;
            border: 1px solid #E2E8F0; border-radius: 10px; padding: 11px 14px;
            background: #F8FAFC;
        }
        .field-display i {
            color: #00A3FF; font-size: 14px; width: 18px; text-align: center;
        }
        .field-display span {
            font-size: 13.5px; font-weight: 700; color: #1E293B;
        }

        .note-box {
            margin-top: 16px; background: #F0F9FF; border: 1px solid #BAE6FD; color: #0284C7;
            border-radius: 12px; padding: 12px 14px; font-size: 11.5px; text-align: left;
            display: flex; gap: 8px; align-items: flex-start; line-height: 1.5;
        }

        /* ====== MODAL EDIT PROFIL ====== */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45);
            display: none; align-items: center; justify-content: center;
            z-index: 999; padding: 20px;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: #fff; border-radius: 20px; width: 100%; max-width: 400px;
            max-height: 90vh; overflow-y: auto; padding: 26px;
        }
        .modal-box h2 { font-size: 16px; font-weight: 800; color: #0F172A; margin-bottom: 2px; }
        .modal-box .modal-sub { font-size: 12px; color: #94A3B8; margin-bottom: 18px; }

        .form-error-box {
            background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626;
            border-radius: 10px; padding: 12px 14px; font-size: 12px; margin-bottom: 16px;
        }
        .form-error-box ul { margin: 6px 0 0 18px; }

        .foto-upload-box {
            border: 2px dashed #BAE6FD; border-radius: 16px; background: #F8FBFF;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 18px 0 22px; margin-bottom: 18px; position: relative;
        }
        .foto-preview {
            width: 84px; height: 84px; border-radius: 50%; object-fit: cover;
            background: #00A3FF; color: #fff; display: flex; align-items: center;
            justify-content: center; font-weight: 800; font-size: 28px;
        }
        .foto-edit-label {
            position: absolute; bottom: 14px; left: 50%; transform: translateX(28px);
            background: #00A3FF; color: #fff; width: 24px; height: 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            font-size: 11px;
        }

        /* Tombol X: menandai foto profil mau dihapus (baru dihapus saat Simpan Perubahan) */
        .btn-hapus-foto {
            position: absolute; top: 10px; left: 50%; margin-left: 22px;
            width: 24px; height: 24px; border-radius: 50%;
            background: #DC2626; color: #fff; border: 2px solid #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; cursor: pointer; padding: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: transform 0.15s ease, background 0.2s ease;
        }
        .btn-hapus-foto:hover { background: #B91C1C; transform: scale(1.1); }

        .field-input {
            display: flex; align-items: center; border: 1px solid #E2E8F0; border-radius: 10px;
            padding: 10px 12px; background: #fff; position: relative;
        }
        .field-input.disabled { background: #F8FAFC; }
        .field-input i.leading { color: #94A3B8; margin-right: 8px; font-size: 13px; }
        .field-input input {
            border: none; outline: none; flex: 1; font-size: 13px; font-family: inherit;
            background: transparent; color: #1E293B;
        }
        .field-input input:disabled { color: #94A3B8; }
        .toggle-eye { background: none; border: none; color: #94A3B8; cursor: pointer; font-size: 13px; }

        .modal-actions { display: flex; gap: 10px; margin-top: 20px; }
        .btn-simpan {
            flex: 1; background: #00A3FF; color: #fff; border: none; padding: 11px 0;
            border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer;
        }
        .btn-batal {
            flex: 1; background: #fff; color: #64748B; border: 1px solid #E2E8F0; padding: 11px 0;
            border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer;
        }

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
        <a href="/dashboard" class="btn-back-dashboard"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="profile-card">
            <div class="avatar-lg">
                @if ($siswa->foto)
                    <img src="{{ asset('image/' . $siswa->foto) }}" alt="Foto Profil">
                @else
                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                @endif
            </div>
            <h2>{{ $siswa->nama }}</h2>
            <p class="sub">{{ $siswa->kelas }}</p>
            <br>
            <button type="button" class="btn-edit-profil" onclick="bukaModalEditProfil()">
                <i class="fa-solid fa-pen"></i> Edit Profil
            </button>

            <div class="field-group">
                <label>NIS</label>
                <div class="field-display"><i class="fa-solid fa-id-card"></i><span>{{ $siswa->nis }}</span></div>
            </div>
            <div class="field-group">
                <label>Kelas</label>
                <div class="field-display"><i class="fa-solid fa-school"></i><span>{{ $siswa->kelas }}</span></div>
            </div>
            <div class="field-group">
                <label>Jenis Kelamin</label>
                <div class="field-display"><i class="fa-solid fa-venus-mars"></i><span>{{ $siswa->jenis_kelamin }}</span></div>
            </div>
            <div class="field-group">
                <label>Username</label>
                <div class="field-display"><i class="fa-solid fa-at"></i><span>{{ $siswa->username }}</span></div>
            </div>
            <div class="field-group">
                <label>Password</label>
                <div class="field-display"><i class="fa-solid fa-lock"></i><span>••••••••</span></div>
            </div>

            <div class="note-box">
                <i class="fa-solid fa-circle-info"></i>
                <span>NIS, kelas, dan username hanya bisa diubah oleh panitia OSIS/admin. Kamu bisa ganti atau hapus foto profil dan ganti password lewat tombol "Edit Profil".</span>
            </div>
        </div>
    </div>

    <!-- ====== MODAL EDIT PROFIL SISWA ====== -->
    <div class="modal-overlay" id="modalEditProfil">
        <div class="modal-box">
            <h2>Profil Saya</h2>
            <p class="modal-sub">Kelola foto dan password akun kamu</p>

            @if ($errors->any())
                <div class="form-error-box">
                    <strong>Ada kesalahan input:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('siswa.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="foto-upload-box">
                    @if ($siswa->foto)
                        <img id="previewFoto" src="{{ asset('image/' . $siswa->foto) }}" class="foto-preview">
                    @else
                        <div id="previewFoto" class="foto-preview">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</div>
                    @endif

                    <!-- Tombol X: tandai foto untuk dihapus (tampil hanya kalau ada foto) -->
                    <button type="button" class="btn-hapus-foto" id="btnHapusFoto"
                        onclick="hapusFotoProfil()"
                        title="Hapus foto profil"
                        style="{{ $siswa->foto ? '' : 'display:none;' }}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    <label for="fotoInput" class="foto-edit-label"><i class="fa-solid fa-pen"></i></label>
                    <input type="file" name="foto" id="fotoInput" accept="image/*" style="display:none" onchange="previewFotoBaru(this)">
                    <input type="hidden" name="hapus_foto" id="hapusFotoFlag" value="0">
                </div>

                <div class="field-input disabled" style="margin-bottom:14px;">
                    <i class="fa-solid fa-user leading"></i>
                    <input type="text" value="{{ $siswa->nama }}" disabled>
                </div>

                <div class="field-input disabled" style="margin-bottom:14px;">
                    <i class="fa-solid fa-at leading"></i>
                    <input type="text" value="{{ $siswa->username }}" disabled>
                </div>

                <div class="field-input">
                    <i class="fa-solid fa-lock leading"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="Kosongkan jika tidak diganti" autocomplete="new-password">
                    <button type="button" class="toggle-eye" onclick="toggleLihatPassword()">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                    <button type="button" class="btn-batal" onclick="tutupModalEditProfil()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simpan kondisi awal foto, supaya tombol Batal bisa mengembalikannya
        const previewAwalHTML = document.getElementById('previewFoto').outerHTML;
        const adaFotoAwal = {{ $siswa->foto ? 'true' : 'false' }};
        const inisialNama = @json(strtoupper(substr($siswa->nama, 0, 1)));

        function bukaModalEditProfil() {
            document.getElementById('modalEditProfil').classList.add('active');
        }

        function tutupModalEditProfil() {
            document.getElementById('modalEditProfil').classList.remove('active');
            resetFormFoto();
        }

        // Kembalikan foto di modal ke kondisi awal (dipakai saat Batal)
        function resetFormFoto() {
            document.getElementById('fotoInput').value = '';
            document.getElementById('hapusFotoFlag').value = '0';

            const sekarang = document.getElementById('previewFoto');
            const pembungkus = document.createElement('div');
            pembungkus.innerHTML = previewAwalHTML;
            sekarang.replaceWith(pembungkus.firstElementChild);

            document.getElementById('btnHapusFoto').style.display = adaFotoAwal ? 'flex' : 'none';
        }

        function previewFotoBaru(input) {
            if (input.files && input.files[0]) {
                const url = URL.createObjectURL(input.files[0]);
                const preview = document.getElementById('previewFoto');
                if (preview.tagName === 'IMG') {
                    preview.src = url;
                } else {
                    const img = document.createElement('img');
                    img.id = 'previewFoto';
                    img.className = 'foto-preview';
                    img.src = url;
                    preview.replaceWith(img);
                }

                // Foto baru dipilih -> batalkan penanda hapus, tampilkan tombol X
                document.getElementById('hapusFotoFlag').value = '0';
                document.getElementById('btnHapusFoto').style.display = 'flex';
            }
        }

        function hapusFotoProfil() {
            // Batalkan foto baru kalau sempat dipilih
            document.getElementById('fotoInput').value = '';

            // Ganti preview dengan huruf inisial (sama seperti tampilan tanpa foto)
            const sekarang = document.getElementById('previewFoto');
            const inisial = document.createElement('div');
            inisial.id = 'previewFoto';
            inisial.className = 'foto-preview';
            inisial.textContent = inisialNama;
            sekarang.replaceWith(inisial);

            // Tandai: foto benar-benar dihapus saat "Simpan Perubahan" diklik
            document.getElementById('hapusFotoFlag').value = '1';

            // Sembunyikan tombol X karena sudah tidak ada foto untuk dihapus
            document.getElementById('btnHapusFoto').style.display = 'none';
        }

        function toggleLihatPassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Kalau ada error validasi, buka lagi modalnya supaya pesan errornya kelihatan
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', bukaModalEditProfil);
        @endif
    </script>

</body>
</html>
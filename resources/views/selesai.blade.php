<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Berhasil - E-Voting OSIS SMK Pertiwi Kuningan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body {
            background-color: #F8FAFC;
            color: #1E293B;
            min-height: 100vh;
            min-height: 100dvh; /* pas dengan area layar HP (address bar ikut dihitung) */
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .navbar {
            background-color: #FFFFFF; padding: 16px 40px; display: flex;
            align-items: center; justify-content: space-between; border-bottom: 1px solid #E2E8F0;
        }
        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .brand img { width: 52px; height: auto; }
        .brand-text h2 { font-size: 15px; font-weight: 800; color: #025088; line-height: 1.2; text-transform: uppercase; }
        .brand-text p { font-size: 11px; color: #00A3FF; font-weight: 600; }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            color: #DC2626;
            background: #FEF2F2;
            padding: 8px 14px;
            border-radius: 20px;
            transition: background 0.2s ease;
        }
        .btn-logout:hover {
            background: #FEE2E2;
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

        .main-container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px; }

        .success-card {
            background: #FFFFFF; width: 100%; max-width: 480px; border-radius: 24px; padding: 40px 32px;
            text-align: center; box-shadow: 0 20px 40px rgba(0, 163, 255, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #E0F2FE; position: relative; z-index: 2;
        }

        .icon-circle {
            width: 80px; height: 80px; background: #00E676; color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
            font-size: 36px; box-shadow: 0 10px 25px rgba(0, 230, 118, 0.35);
        }

        .success-card h1 { font-size: 26px; font-weight: 800; color: #0F172A; margin-bottom: 12px; }
        .success-card p.description { font-size: 13px; color: #64748B; line-height: 1.6; margin-bottom: 28px; }

        .quote-box {
            background-color: #F0F9FF; border-radius: 12px; padding: 14px 20px; color: #0284C7;
            font-weight: 700; font-style: italic; font-size: 13px; letter-spacing: 0.5px;
            margin-bottom: 30px; text-transform: uppercase;
        }

        .card-footer { display: flex; align-items: center; justify-content: center; gap: 8px; color: #94A3B8; font-size: 12px; font-weight: 500; }
        .card-footer img { width: 20px; height: auto; opacity: 0.8; }

        #confetti-canvas {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none; z-index: 1;
        }

        @media (max-width: 480px) {
            .navbar { padding: 14px 20px; }
            .brand-text h2 { font-size: 12px; }
            .brand-text p { font-size: 9px; }
            .btn-logout { font-size: 11px; padding: 7px 12px; }
            .main-container { padding: 24px 16px; }
            .success-card { padding: 28px 20px; }
            .success-card h1 { font-size: 21px; }
            .icon-circle { width: 64px; height: 64px; font-size: 28px; }
        }

        /* ===== Layar pendek: rapatkan sedikit agar tetap pas 1 layar ===== */
        @media (max-height: 600px) {
            .navbar { padding: 10px 16px; }
            .brand img { width: 40px; }
            .main-container { padding: 12px 16px; }
            .success-card { padding: 20px 18px; }
            .icon-circle { width: 52px; height: 52px; font-size: 22px; margin-bottom: 14px; }
            .success-card h1 { font-size: 19px; margin-bottom: 8px; }
            .success-card p.description { margin-bottom: 16px; }
            .quote-box { padding: 10px 14px; margin-bottom: 16px; font-size: 12px; }
        }

        /* Layar sangat pendek: sembunyikan elemen yang kurang penting */
        @media (max-height: 500px) {
            .quote-box { display: none; }
        }
    </style>
</head>
<body>

    <canvas id="confetti-canvas"></canvas>

    <nav class="navbar">
        <a href="#" class="brand">
            <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS">
            <div class="brand-text">
                <h2>OSIS SMK Pertiwi Kuningan</h2>
                <p>E-Voting OSIS {{ $tahunAjaran }}</p>
            </div>
        </a>

        <a href="#" class="btn-logout" onclick="bukaModalLogout(event)">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
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

    <div class="main-container">
        <div class="success-card">
            <div class="icon-circle">
                <i class="fa-solid fa-check"></i>
            </div>

            <h1>Voting Berhasil!</h1>

            <p class="description">
                Terima kasih telah menggunakan hak pilih Anda.<br>
                Suara Anda sangat berarti bagi masa depan<br>
                OSIS SMK Pertiwi Kuningan.
            </p>

            <div class="quote-box">
                "SUARAMU. MASA DEPAN SEKOLAH KITA."
            </div>

            <div class="card-footer">
                <img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS Mini">
                <span>OSIS SMK Pertiwi Kuningan · {{ $tahunAjaran }}</span>
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

        window.addEventListener('DOMContentLoaded', () => {
            var myCanvas = document.getElementById('confetti-canvas');
            var myConfetti = confetti.create(myCanvas, {
                resize: true,
                useWorker: true
            });

            var duration = 3 * 1000;
            var end = Date.now() + duration;

            (function frame() {
                myConfetti({
                    particleCount: 3,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#00A3FF', '#00E676', '#FFD600', '#FF4081']
                });
                myConfetti({
                    particleCount: 3,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#00A3FF', '#00E676', '#FFD600', '#FF4081']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            })();
        });
    </script>
</body>
</html>
{{-- Perbaikan tampilan HP untuk SEMUA halaman admin (dipakai layout cms & layout pemilihan).
     Diletakkan paling akhir di <head> supaya menimpa gaya bawaan di layar kecil. --}}
<style>
    html, body { overflow-x: hidden; -webkit-text-size-adjust: 100%; }
    img { max-width: 100%; }
    .subnav { scrollbar-width: none; -webkit-overflow-scrolling: touch; scroll-snap-type: x proximity; }
    .subnav::-webkit-scrollbar { display: none; }
    .subnav a { scroll-snap-align: start; flex-shrink: 0; }

    /* ---------- TABLET ---------- */
    @media (max-width: 900px) {
        .tbl-wrap, .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tbl-wrap table { min-width: 560px; }
    }

    /* ---------- HP ---------- */
    @media (max-width: 760px) {
        /* header: ringkas, tidak lagi menempel & memakan setengah layar */
        .topwrap { position: static !important; padding: 8px 10px 0 !important; background: transparent; }
        .topwrap .topbar { grid-template-columns: minmax(0, 1fr) auto !important; gap: 8px 10px; padding: 7px 10px !important;
                           border-radius: 22px !important; box-shadow: 0 4px 16px rgba(7, 89, 133, .12); }
        .topwrap .brand { min-width: 0; gap: 8px; }
        .topwrap .brand img { height: 36px !important; max-width: 40px !important; }
        .topwrap .brand b { font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topwrap .brand small { font-size: 11px; }
        .topwrap .user { gap: 8px; }
        .topwrap .user .who { display: block; }
        .topwrap .user .who b { display: none; }
        .topwrap .user .who a { font-size: 12px; font-weight: 600; padding: 6px 4px; display: inline-block; }
        .topwrap .avatar { width: 36px !important; height: 36px !important; }
        /* Dashboard | Pemilihan jadi tombol dua kolom yang ramping */
        .topwrap .pills { grid-column: 1 / -1 !important; grid-row: 2; gap: 6px; background: #f0f7ff; padding: 4px; border-radius: 999px; }
        .topwrap .pills .pill { flex: 1; text-align: center; padding: 8px 10px !important; font-size: 13px !important; border-width: 0 !important; background: transparent; }
        .topwrap .pills .pill.on { background: var(--primary); color: #fff; box-shadow: 0 3px 8px rgba(5, 104, 159, .25); }
        .topwrap .subnav { margin-top: 8px !important; padding: 6px !important; gap: 6px !important; border-radius: 14px !important; }
        .topwrap .subnav a { padding: 8px 14px !important; font-size: 13px !important; }

        /* isi halaman */
        .page, .page-wrap { padding: 14px 12px 70px !important; }
        .page-head, .header-action { gap: 10px; margin-bottom: 14px; }
        .page-title, .header-action h2 { font-size: 21px !important; }
        .page-head .btn, .header-action .btn-group { width: 100%; }
        .header-action .btn-group { display: flex; gap: 8px; }
        .header-action .btn-group > * { flex: 1 1 0; justify-content: center; text-align: center; }
        .card { padding: 16px !important; border-radius: 18px !important; }
        .card-head { align-items: flex-start; }
        .row2, .two, .form-row-2 { grid-template-columns: 1fr !important; }
        .actions .btn { flex: 1 1 auto; justify-content: center; }
        .actions { width: 100%; }
        td .actions { width: auto; flex-wrap: nowrap; }
        td .actions .btn { flex: 0 0 auto; }

        /* tabel: bisa digeser ke samping, kolom tidak saling menumpuk */
        .table, .table-hasil { display: block; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; }
        .tbl-wrap { margin: 0 -4px; padding: 0 4px; }
        .tbl-wrap table { min-width: 520px; }
        th, td { padding: 10px 8px; }

        /* isian: 16px supaya HP tidak otomatis zoom saat mengetik */
        input[type=text], input[type=email], input[type=password], input[type=number], input[type=date],
        input[type=search], input[type=tel], select, textarea { font-size: 16px !important; }
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-form > * { width: 100%; min-width: 0 !important; flex: 1 1 auto; }

        /* jendela pop-up: muat di layar, isi bisa digulir */
        .modal-overlay, .mlogout, .mprofil { padding: 12px !important; align-items: flex-end !important; }
        .modal-box, .mlogout-box, .mprofil-box { max-height: 92vh; overflow-y: auto; border-radius: 22px !important; padding: 22px 18px !important; max-width: 100% !important; }
        .modal-actions, .form-actions, .mp-actions { flex-wrap: wrap; }
        .modal-actions > *, .form-actions > *, .mp-actions > * { flex: 1 1 140px; }

        .toast { left: 12px !important; right: 12px !important; bottom: 12px !important; max-width: none !important; }
        .btn, .btn-primary, .btn-cancel, button { touch-action: manipulation; }
    }

    @media (max-width: 380px) {
        .topwrap .brand b { font-size: 12px; }
        .topwrap .pills .pill { font-size: 12.5px !important; }
    }
</style>

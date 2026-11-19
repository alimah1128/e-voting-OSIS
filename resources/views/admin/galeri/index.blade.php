@extends('admin.layouts.cms')
@section('title', 'Galeri')

@push('styles')
<style>
    .up { display:grid; grid-template-columns:1fr 1fr; gap:24px; align-items:center; }
    .drop { border:2px dashed #7dd3fc; background:#f0f9ff; border-radius:18px; padding:26px 18px; text-align:center; transition:.2s; }
    .drop.over { background:#e0f2fe; border-color:var(--primary); }
    .drop b { display:block; color:var(--navy); font-size:15px; margin-bottom:4px; }
    .drop p { color:#64748b; font-size:12.5px; margin-bottom:14px; }
    .gal { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
    .gcard { background:#fff; border-radius:18px; padding:8px; box-shadow:0 6px 22px rgba(2,132,199,.07); }
    .gcard .photo-preview { position:relative; display:block; width:100%; padding:0; border:0; background:none; border-radius:12px; cursor:zoom-in; overflow:hidden; }
    .gcard .photo-preview img { width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:12px; display:block; }
    .gcard .photo-preview::after { content:''; position:absolute; inset:0; display:grid; place-items:center; color:#fff; background:rgba(15,23,42,.38); opacity:0; transition:opacity .2s; border-radius:12px; }
    .gcard .photo-preview::before { content:''; position:absolute; z-index:1; left:50%; top:50%; width:22px; height:22px; transform:translate(-50%,-50%); opacity:0; background:center / contain no-repeat url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='7'/%3E%3Cpath d='m20 20-4-4'/%3E%3C/svg%3E"); }
    .gcard .photo-preview:hover::after, .gcard .photo-preview:focus-visible::after,
    .gcard .photo-preview:hover::before, .gcard .photo-preview:focus-visible::before { opacity:1; }
    .gcard .photo-preview:focus-visible { outline:none; box-shadow:none; }
    .edit-upload { display:flex; align-items:center; gap:12px; padding:12px; border:1px dashed #7dd3fc; border-radius:14px; background:#f0f9ff; cursor:pointer; color:var(--navy); font-size:13px; font-weight:600; }
    .edit-upload:hover { background:#e0f2fe; }
    .edit-upload-icon { display:grid; place-items:center; width:38px; height:38px; border-radius:11px; background:#dbeafe; font-size:19px; }
    #previewEdit { display:block; width:100%; max-height:240px; object-fit:contain; margin-top:12px; border-radius:12px; background:#f1f5f9; }

    #dlgFoto { width:min(94vw,1100px); height:min(92vh,820px); padding:54px 20px 20px; background:transparent; box-shadow:none; overflow:visible; }
    #dlgFoto::backdrop { background:rgba(0,0,0,.82); }
    #dlgFoto img { display:block; max-width:100%; max-height:100%; width:auto; height:auto; margin:auto; object-fit:contain; border-radius:10px; }
    #btnTutupFoto { position:absolute; top:0; right:0; width:42px; height:42px; border:0; border-radius:50%; background:#fff; color:#111827; font-size:28px; line-height:1; cursor:pointer; }
    @media (prefers-reduced-motion:reduce) { .gcard .photo-preview::after { transition:none; } }
    #namaFoto { margin:12px 0 0; min-height:18px; font-weight:600; color:var(--navy); }
    #previewFoto { display:flex; flex-wrap:wrap; justify-content:center; gap:12px; margin-top:12px; }
    #previewFoto:empty { display:none; }
    #previewFoto { align-items:flex-start; }
    .pv { position:relative; width:calc(50% - 6px); }
    .pv img { display:block; width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:16px; box-shadow:0 4px 14px rgba(2,132,199,.22); background:#e0f2fe; }
    /* ===== Setelah foto dipilih: kotak jadi foto penuh, tanpa teks ===== */
    .drop { position:relative; }
    .drop.has-foto { padding:0; overflow:hidden; background:#000; }
    .drop.has-foto > b, .drop.has-foto > p:not(.drop-warn), .drop.has-foto #lblPilih,
    .drop.has-foto .drop-tools, .drop.has-foto .pv small { display:none; }
    .drop.has-foto #previewFoto { margin:0; gap:4px; width:100%; }
    .drop.has-foto .pv { width:calc(50% - 2px); }
    .drop.has-foto .pv img { border-radius:0; box-shadow:none; }
    .drop.has-foto .pv.single { width:100%; }
    .drop.has-foto .pv.single img { aspect-ratio:4/3; object-fit:cover; }
    .drop.has-foto .pv-x { top:12px; right:12px; width:32px; height:32px; font-size:18px; }
    .drop.has-foto .drop-warn { position:absolute; left:12px; right:70px; bottom:12px; margin:0; padding:6px 10px; border-radius:10px; background:#fee2e2; text-align:left; }
    .pv-add { display:none; position:absolute; right:12px; bottom:12px; width:44px; height:44px; border-radius:50%; background:var(--primary); color:#fff;
              font-size:26px; line-height:1; cursor:pointer; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(0,0,0,.3); }
    .drop.has-foto .pv-add { display:flex; }
    .drop.has-foto .pv-add[hidden] { display:none !important; }
    .pv .pv-x { position:absolute; top:8px; right:8px; width:26px; height:26px; border-radius:50%; border:2px solid #fff; background:#dc2626; color:#fff;
                font-size:15px; line-height:1; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,.2); transition:.15s; padding:0; }
    .pv .pv-x:hover { background:#b91c1c; transform:scale(1.12); }
    .pv small { display:block; margin-top:6px; font-size:11px; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .drop-tools { display:flex; gap:8px; justify-content:center; margin-top:14px; flex-wrap:wrap; }
    .drop-warn { margin-top:10px; font-size:12px; color:#b91c1c; }
    .drop-warn:empty { display:none; }
    .gcard .cap { font-size:12.5px; color:#64748b; margin:10px 4px; min-height:34px; word-break:break-word; }
    .gcard .cap.none { color:#cbd5e1; font-style:italic; }
    .gcard .actions { display:grid; grid-template-columns:1fr 1fr; gap:6px; }
    .gcard .actions form { display:block; }
    .gcard .actions .btn { width:100%; justify-content:center; }
    .sub-head { display:flex; justify-content:space-between; align-items:center; margin:22px 2px 12px; }
    .sub-head h3 { color:var(--navy); font-size:15px; }
    .sub-head span { color:#64748b; font-size:13px; }
    dialog { position:fixed; inset:0; margin:auto; border:none; border-radius:20px; padding:24px; width:min(92vw,420px); max-height:90vh; box-shadow:0 20px 50px rgba(0,0,0,.25); font-family:inherit; }
    dialog::backdrop { background:rgba(7,89,133,.45); }
    #dlgHapus { width:min(92vw,380px); padding:30px 28px 26px; text-align:center; }
    .hapus-ic { width:58px; height:58px; margin:0 auto 14px; border-radius:50%; background:#fee2e2; display:grid; place-items:center; font-size:26px; }
    #dlgHapus h3 { color:#0f172a; font-size:18px; margin-bottom:6px; }
    .dlg-text { color:#64748b; margin:0 0 16px; font-size:13.5px; line-height:1.6; }
    .hapus-prev { display:flex; align-items:center; gap:12px; text-align:left; padding:8px; margin-bottom:20px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; }
    .hapus-prev img { width:60px; height:60px; border-radius:10px; object-fit:cover; flex-shrink:0; }
    .hapus-prev span { font-size:12.5px; color:#475569; word-break:break-word; }
    .dlg-actions { display:flex; gap:10px; } .dlg-actions .btn { flex:1; justify-content:center; padding:11px 16px; }
    .btn-hapus { background:#dc2626; color:#fff; } .btn-hapus:hover { background:#b91c1c; }
    @media (max-width:1000px){ .gal { grid-template-columns:repeat(3,1fr); } }
    @media (max-width:760px){ .up { grid-template-columns:1fr; } .gal { grid-template-columns:repeat(2,1fr); gap:12px; } }
</style>
@endpush

@section('content')
    @include('admin.partials.page-head', [
        'judul'   => 'Galeri Kegiatan',
        'sub'     => 'Foto-foto kegiatan OSIS. Bisa ditambah, diberi keterangan, dan dihapus.',
        'kembali' => true,
    ])

    {{-- ===== UNGGAH ===== --}}
    <form class="card" id="formUnggah" method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="up">
            <div class="drop" id="drop">
                <b>Seret foto ke sini atau pilih dari perangkat</b>
                <p>Bisa pilih banyak foto sekaligus. Maks 4 MB per foto.</p>
                <label for="fileGaleri" class="btn btn-primary" id="lblPilih">Pilih Foto</label>
                <input type="file" id="fileGaleri" name="foto[]" accept="image/*" multiple hidden>
                <p id="namaFoto" aria-live="polite"></p>
                <div id="previewFoto" aria-label="Preview foto terpilih"></div>
                <div class="drop-tools">
                    <button type="button" class="btn btn-outline btn-sm" id="btnBatalSemua" hidden>Batalkan semua</button>
                </div>
                <p class="drop-warn" id="peringatan" role="alert"></p>
                <label for="fileGaleri" class="pv-add" id="btnTambah" title="Tambah foto lagi" aria-label="Tambah foto lagi">+</label>
            </div>
            <div class="fg" style="margin:0">
                <label class="lb" for="keterangan">Keterangan foto (opsional)</label>
                <input type="text" id="keterangan" name="keterangan" maxlength="150" placeholder="Keterangan singkat">
                <small>Keterangan ini dipakai untuk semua foto yang diunggah sekaligus.</small>
            </div>
        </div>
        <div class="actions" style="justify-content:flex-end;margin-top:18px">
            <button class="btn btn-primary" id="btnSimpan" type="submit" disabled>Simpan Foto</button>
        </div>
    </form>

    {{-- ===== SEMUA FOTO ===== --}}
    <div class="sub-head">
        <h3>Semua Foto</h3>
        <span>{{ $items->count() }} foto</span>
    </div>

    @if($items->count())
        <div class="gal">
            @foreach($items as $g)
                <div class="gcard">
                    <button type="button" class="photo-preview" aria-label="Perbesar {{ $g->keterangan ?: 'foto galeri' }}" data-src="{{ asset('storage/'.$g->foto) }}" data-alt="{{ $g->keterangan ?: 'Foto galeri' }}">
                        <img src="{{ asset('storage/'.$g->foto) }}" alt="{{ $g->keterangan ?: 'Foto galeri' }}" loading="lazy">
                    </button>
                    <div class="cap {{ $g->keterangan ? '' : 'none' }}">{{ $g->keterangan ?: 'Tanpa keterangan' }}</div>
                    <div class="actions">
                        <button type="button" class="btn btn-soft btn-sm btn-edit"
                                data-url="{{ route('admin.galeri.update', $g->id) }}"
                                data-ket="{{ $g->keterangan }}"
                                data-foto="{{ asset('storage/'.$g->foto) }}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus-foto"
                                data-url="{{ route('admin.galeri.destroy', $g->id) }}"
                                data-foto="{{ asset('storage/'.$g->foto) }}"
                                data-ket="{{ $g->keterangan ?: 'Tanpa keterangan' }}">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card"><p class="kosong">Belum ada foto. Unggah foto kegiatan lewat kotak di atas.</p></div>
    @endif

    <dialog id="dlgFoto" aria-label="Pratinjau foto">
        <button type="button" id="btnTutupFoto" aria-label="Tutup pratinjau">&times;</button>
        <img id="imgFotoBesar" alt="">
    </dialog>

    {{-- ===== DIALOG EDIT FOTO ===== --}}
    <dialog id="dlgEdit">
        <form method="POST" id="formEdit" enctype="multipart/form-data">
            @csrf @method('PUT')
            <h3 style="color:var(--navy);margin-bottom:14px">Edit foto dan keterangan</h3>
            <div class="fg">
                <label class="lb" for="fotoEdit">Foto saat ini</label>
                <img id="previewEdit" alt="Foto galeri saat ini">
                <label class="edit-upload" for="fotoEdit"><span class="edit-upload-icon" aria-hidden="true">↥</span><span id="labelFotoEdit">Pilih foto baru <small style="display:block;font-weight:400;color:#64748b">JPG, PNG, maks. 4 MB</small></span></label>
                <input type="file" id="fotoEdit" name="foto" accept="image/*" hidden>
            </div>
            <div class="fg"><label class="lb" for="ketEdit">Keterangan</label>
                <input type="text" id="ketEdit" name="keterangan" maxlength="150" placeholder="Keterangan singkat"></div>
            <div class="actions">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" type="button" id="btnBatal">Batal</button>
            </div>
        </form>
    </dialog>

    <dialog id="dlgHapus" aria-labelledby="judulHapus">
        <div class="hapus-ic" aria-hidden="true">🗑️</div>
        <h3 id="judulHapus">Hapus foto ini?</h3>
        <p class="dlg-text">Foto akan dihapus permanen dari galeri dan tidak dapat dikembalikan.</p>
        <div class="hapus-prev">
            <img id="hapusImg" alt="">
            <span id="hapusKet"></span>
        </div>
        <form method="POST" id="formHapus" action="">
            @csrf @method('DELETE')
            <div class="dlg-actions">
                <button class="btn" type="button" id="btnBatalHapus">Batal</button>
                <button class="btn btn-hapus" type="submit" id="btnYaHapus">Ya, Hapus</button>
            </div>
        </form>
    </dialog>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('formUnggah'),
            input = document.getElementById('fileGaleri'),
            drop = document.getElementById('drop'),
            btnSimpan = document.getElementById('btnSimpan'),
            btnBatalSemua = document.getElementById('btnBatalSemua'),
            namaFoto = document.getElementById('namaFoto'),
            peringatan = document.getElementById('peringatan'),
            previewFoto = document.getElementById('previewFoto'),
            MAKS_FOTO = 20, MAKS_MB = 4,
            terpilih = [], urls = [];

        function kunci(f) { return f.name + '|' + f.size + '|' + f.lastModified; }

        // sinkronkan daftar `terpilih` ke input file asli (yang ikut terkirim saat disimpan)
        function sinkronkanInput() {
            var dt = new DataTransfer();
            terpilih.forEach(function (f) { dt.items.add(f); });
            input.files = dt.files;
        }

        function render() {
            urls.forEach(function (u) { URL.revokeObjectURL(u); });
            urls = [];
            previewFoto.replaceChildren();
            terpilih.forEach(function (file, i) {
                var url = URL.createObjectURL(file), wrap = document.createElement('div'),
                    img = document.createElement('img'), x = document.createElement('button'), nm = document.createElement('small');
                urls.push(url);
                wrap.className = 'pv' + (terpilih.length === 1 ? ' single' : '');
                img.src = url; img.alt = file.name;
                x.type = 'button'; x.className = 'pv-x'; x.innerHTML = '&times;';
                x.title = 'Batalkan foto ini'; x.setAttribute('aria-label', 'Batalkan foto ' + file.name);
                x.addEventListener('click', function () {
                    terpilih.splice(i, 1);
                    peringatan.textContent = '';
                    sinkronkanInput(); render();
                });
                nm.textContent = file.name; nm.title = file.name;
                wrap.append(img, x, nm);
                previewFoto.appendChild(wrap);
            });
            namaFoto.textContent = terpilih.length ? terpilih.length + ' foto dipilih' : '';
            btnSimpan.disabled = !terpilih.length;
            btnBatalSemua.hidden = !terpilih.length;
            drop.classList.toggle('has-foto', terpilih.length > 0);
            document.getElementById('btnTambah').hidden = terpilih.length >= MAKS_FOTO;
        }

        function tambahFile(daftar) {
            var pesan = [];
            Array.from(daftar).forEach(function (f) {
                if (!f.type.startsWith('image/')) { pesan.push(f.name + ' bukan gambar.'); return; }
                if (f.size > MAKS_MB * 1024 * 1024) { pesan.push(f.name + ' lebih dari ' + MAKS_MB + ' MB.'); return; }
                if (terpilih.some(function (t) { return kunci(t) === kunci(f); })) return;
                if (terpilih.length >= MAKS_FOTO) { pesan.length || pesan.push('Maksimal ' + MAKS_FOTO + ' foto sekali unggah.'); return; }
                terpilih.push(f);
            });
            peringatan.textContent = pesan.join(' ');
            sinkronkanInput(); render();
        }

        input.addEventListener('change', function () { tambahFile(input.files); });
        btnBatalSemua.addEventListener('click', function () {
            terpilih = []; peringatan.textContent = '';
            sinkronkanInput(); render();
        });
        form.addEventListener('submit', function (e) {
            if (!terpilih.length) { e.preventDefault(); render(); return; }
            btnSimpan.disabled = true;
            btnSimpan.textContent = 'Mengunggah...';
        });

        // seret & lepas
        ['dragenter', 'dragover'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('over'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('over'); });
        });
        drop.addEventListener('drop', function (e) {
            if (e.dataTransfer.files.length) tambahFile(e.dataTransfer.files);
        });
        render();

        var dlgFoto = document.getElementById('dlgFoto'), imgFotoBesar = document.getElementById('imgFotoBesar');
        document.querySelectorAll('.photo-preview').forEach(function (b) {
            b.addEventListener('click', function () {
                imgFotoBesar.src = b.dataset.src;
                imgFotoBesar.alt = b.dataset.alt;
                dlgFoto.showModal();
            });
        });
        document.getElementById('btnTutupFoto').addEventListener('click', function () { dlgFoto.close(); });
        dlgFoto.addEventListener('click', function (e) { if (e.target === dlgFoto) dlgFoto.close(); });
        dlgFoto.addEventListener('close', function () { imgFotoBesar.removeAttribute('src'); });

        // edit keterangan
        var dlg = document.getElementById('dlgEdit'), fe = document.getElementById('formEdit'), ket = document.getElementById('ketEdit'), fotoEdit = document.getElementById('fotoEdit'), previewEdit = document.getElementById('previewEdit'), previewEditUrl;
        document.querySelectorAll('.btn-edit').forEach(function (b) {
            b.addEventListener('click', function () {
                fe.action = b.getAttribute('data-url');
                ket.value = b.getAttribute('data-ket') || '';
                fotoEdit.value = '';
                previewEdit.src = b.getAttribute('data-foto');
                previewEdit.alt = 'Foto saat ini';
                document.getElementById('labelFotoEdit').firstChild.textContent = 'Pilih foto baru ';
                dlg.showModal();
                ket.focus();
            });
        });
        fotoEdit.addEventListener('change', function () {
            if (previewEditUrl) URL.revokeObjectURL(previewEditUrl);
            if (!fotoEdit.files.length) { previewEdit.hidden = true; return; }
            previewEditUrl = URL.createObjectURL(fotoEdit.files[0]);
            previewEdit.src = previewEditUrl;
            previewEdit.alt = 'Preview foto baru';
            document.getElementById('labelFotoEdit').firstChild.textContent = fotoEdit.files[0].name + ' ';

        });
        document.getElementById('btnBatal').addEventListener('click', function () { dlg.close(); });
        dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });
        dlg.addEventListener('close', function () { if (previewEditUrl) URL.revokeObjectURL(previewEditUrl); previewEditUrl = null; });

        var dlgHapus = document.getElementById('dlgHapus'), formHapus = document.getElementById('formHapus'),
            hapusImg = document.getElementById('hapusImg'), hapusKet = document.getElementById('hapusKet'),
            btnYaHapus = document.getElementById('btnYaHapus');
        document.querySelectorAll('.btn-hapus-foto').forEach(function (b) {
            b.addEventListener('click', function () {
                formHapus.setAttribute('action', b.dataset.url);
                hapusImg.src = b.dataset.foto;
                hapusKet.textContent = b.dataset.ket;
                btnYaHapus.disabled = false;
                btnYaHapus.textContent = 'Ya, Hapus';
                dlgHapus.showModal();
            });
        });
        formHapus.addEventListener('submit', function () {
            btnYaHapus.disabled = true;      // cegah klik ganda (klik kedua = foto sudah hilang)
            btnYaHapus.textContent = 'Menghapus...';
        });
        document.getElementById('btnBatalHapus').addEventListener('click', function () { dlgHapus.close(); });
        dlgHapus.addEventListener('click', function (e) { if (e.target === dlgHapus) dlgHapus.close(); });
    })();
</script>
@endpush
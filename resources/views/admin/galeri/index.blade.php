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
    #previewFoto img { width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:10px; }
    #previewFoto { display:grid; grid-template-columns:repeat(auto-fill,minmax(100px,1fr)); gap:8px; margin-top:14px; }
    #previewFoto img { width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:10px; }
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
    .dlg-actions { display:flex; gap:8px; } .dlg-actions .btn { min-width:90px; justify-content:center; }
    .dlg-text { color:#64748b; margin:0 0 18px; font-size:14px; }
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
                        <form method="POST" action="{{ route('admin.galeri.destroy', $g->id) }}" class="form-hapus">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                        </form>
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
        <h3 id="judulHapus" style="color:var(--navy);margin-bottom:8px">Hapus foto?</h3>
        <p class="dlg-text">Foto yang dihapus tidak dapat dikembalikan.</p>
        <form method="POST" id="formHapus">
            @csrf @method('DELETE')
            <div class="dlg-actions">
                <button class="btn btn-danger" type="submit">Hapus</button>
                <button class="btn" type="button" id="btnBatalHapus">Batal</button>
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
            lbl = document.getElementById('lblPilih'),
            btnSimpan = document.getElementById('btnSimpan'),
            namaFoto = document.getElementById('namaFoto'),
            previewFoto = document.getElementById('previewFoto'),
            previewUrls = [];

        function tampilkanPreview() {
            previewUrls.forEach(function (url) { URL.revokeObjectURL(url); });
            previewUrls = [];
            previewFoto.replaceChildren();
            Array.from(input.files).forEach(function (file) {
                if (!file.type.startsWith('image/')) return;
                var url = URL.createObjectURL(file), img = document.createElement('img');
                previewUrls.push(url);
                img.src = url;
                img.alt = file.name;
                img.title = file.name;
                previewFoto.appendChild(img);
            });
        }
        function perbaruiTombol() {
            btnSimpan.disabled = !input.files.length;
        }
        input.addEventListener('change', function () {
            namaFoto.textContent = input.files.length ? input.files.length + ' foto dipilih' : '';
            tampilkanPreview();
            perbaruiTombol();
        });
        document.getElementById('keterangan').addEventListener('input', perbaruiTombol);
        form.addEventListener('submit', function (e) {
            if (!input.files.length) {
                e.preventDefault();
                perbaruiTombol();
                return;
            }
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
            if (!e.dataTransfer.files.length) return;
            input.files = e.dataTransfer.files;
            namaFoto.textContent = input.files.length + ' foto dipilih';
            tampilkanPreview();
            perbaruiTombol();
        });

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

        var dlgHapus = document.getElementById('dlgHapus'), formHapus = document.getElementById('formHapus');
        document.querySelectorAll('.form-hapus').forEach(function (f) {
            f.addEventListener('submit', function (e) {
                e.preventDefault();
                formHapus.action = f.action;
                dlgHapus.showModal();
            });
        });
        document.getElementById('btnBatalHapus').addEventListener('click', function () { dlgHapus.close(); });
        dlgHapus.addEventListener('click', function (e) { if (e.target === dlgHapus) dlgHapus.close(); });
    })();
</script>
@endpush
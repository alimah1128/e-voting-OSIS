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
    .gcard img { width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:12px; display:block; }
    .gcard .cap { font-size:12.5px; color:#64748b; margin:10px 4px; min-height:34px; word-break:break-word; }
    .gcard .cap.none { color:#cbd5e1; font-style:italic; }
    .gcard .actions { display:grid; grid-template-columns:1fr 1fr; gap:6px; }
    .gcard .actions form { display:block; }
    .gcard .actions .btn { width:100%; justify-content:center; }
    .sub-head { display:flex; justify-content:space-between; align-items:center; margin:22px 2px 12px; }
    .sub-head h3 { color:var(--navy); font-size:15px; }
    .sub-head span { color:#64748b; font-size:13px; }
    dialog { border:none; border-radius:20px; padding:24px; width:min(92vw,420px); box-shadow:0 20px 50px rgba(0,0,0,.25); font-family:inherit; }
    dialog::backdrop { background:rgba(7,89,133,.45); }
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
            </div>
            <div class="fg" style="margin:0">
                <label class="lb" for="keterangan">Keterangan foto (opsional)</label>
                <input type="text" id="keterangan" name="keterangan" maxlength="150" placeholder="Keterangan singkat">
                <small>Isi dulu sebelum memilih foto. Keterangan ini dipakai untuk semua foto yang diunggah sekaligus.</small>
            </div>
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
                    <img src="{{ asset('storage/'.$g->foto) }}" alt="{{ $g->keterangan ?: 'Foto galeri' }}" loading="lazy">
                    <div class="cap {{ $g->keterangan ? '' : 'none' }}">{{ $g->keterangan ?: 'Tanpa keterangan' }}</div>
                    <div class="actions">
                        <button type="button" class="btn btn-soft btn-sm btn-edit"
                                data-url="{{ route('admin.galeri.update', $g->id) }}"
                                data-ket="{{ $g->keterangan }}">Edit</button>
                        <form method="POST" action="{{ route('admin.galeri.destroy', $g->id) }}" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card"><p class="kosong">Belum ada foto. Unggah foto kegiatan lewat kotak di atas.</p></div>
    @endif

    {{-- ===== DIALOG EDIT KETERANGAN ===== --}}
    <dialog id="dlgEdit">
        <form method="POST" id="formEdit">
            @csrf @method('PUT')
            <h3 style="color:var(--navy);margin-bottom:14px">Edit keterangan foto</h3>
            <div class="fg"><label class="lb" for="ketEdit">Keterangan</label>
                <input type="text" id="ketEdit" name="keterangan" maxlength="150" placeholder="Keterangan singkat"></div>
            <div class="actions">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" type="button" id="btnBatal">Batal</button>
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
            lbl = document.getElementById('lblPilih');

        function kirim() {
            if (!input.files.length) return;
            lbl.textContent = 'Mengunggah ' + input.files.length + ' foto...';
            form.submit();
        }
        input.addEventListener('change', kirim);

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
            kirim();
        });

        // edit keterangan
        var dlg = document.getElementById('dlgEdit'), fe = document.getElementById('formEdit'), ket = document.getElementById('ketEdit');
        document.querySelectorAll('.btn-edit').forEach(function (b) {
            b.addEventListener('click', function () {
                fe.action = b.getAttribute('data-url');
                ket.value = b.getAttribute('data-ket') || '';
                dlg.showModal();
                ket.focus();
            });
        });
        document.getElementById('btnBatal').addEventListener('click', function () { dlg.close(); });
        dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });
    })();
</script>
@endpush
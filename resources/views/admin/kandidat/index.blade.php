@extends('layouts.admin')

@section('title', 'Kelola Kandidat')

@section('content')
<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>

    <div class="header-action">
        <div>
            <h2>Kelola Kandidat</h2>
            <p>Manajemen pasangan kandidat E-Voting OSIS</p>
        </div>
        @if ($periodeAktif)
            <button type="button" class="btn-primary" onclick="bukaModalTambah()">+ Tambah Kandidat</button>
        @endif
    </div>

    @if (! $periodeAktif)
        <div class="form-error-box" style="max-width:none;">
            Tidak ada periode yang sedang aktif. Aktifkan periode dulu di menu
            <a href="{{ route('admin.periode.index') }}" style="color:#DC2626; font-weight:700;">Kelola Periode</a>
            sebelum menambahkan kandidat.
        </div>
    @endif

    <div class="kandidat-grid">
        @forelse ($kandidats as $kandidat)
            <div class="kandidat-card">
                <div class="kandidat-photo-wrap">
                    <span class="kandidat-badge">No. {{ $kandidat->no_urut }}</span>
                    @if ($kandidat->foto)
                        <img src="{{ asset('image/' . $kandidat->foto) }}" alt="{{ $kandidat->nama_ketua }}">
                    @else
                        <div class="kandidat-photo-placeholder"><i class="fa-regular fa-image"></i></div>
                    @endif
                </div>
                <div class="kandidat-card-body">
                    <h3>{{ $kandidat->nama_ketua }}</h3>
                    <p class="kandidat-wakil">& {{ $kandidat->nama_wakil }} · {{ $kandidat->kelas_ketua }}</p>
                    <p class="kandidat-visi">{{ \Illuminate\Support\Str::limit($kandidat->visi, 90) }}</p>
                    <div class="kandidat-actions">
                        <button type="button" class="btn-edit" onclick='bukaModalEdit(@json($kandidat))'>
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        <button type="button" class="btn-delete"
                            onclick="bukaModalHapusKandidat({{ $kandidat->id }}, '{{ addslashes($kandidat->nama_ketua) }} & {{ addslashes($kandidat->nama_wakil) }}', {{ $kandidat->foto ? "'" . asset('image/' . $kandidat->foto) . "'" : 'null' }})">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="empty-note">Belum ada data kandidat. Klik "+ Tambah Kandidat" untuk menambahkan.</p>
        @endforelse
    </div>
</div>

<!-- MODAL TAMBAH/EDIT KANDIDAT (dipakai bareng) -->
<div class="modal-overlay" id="modalKandidat">
    <div class="modal-box modal-box-lg">
        <h3 class="modal-form-title" id="modalKandidatTitle">Tambah Kandidat</h3>

        <form id="formKandidat" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="methodOverride" value="POST">

            <div class="field-group">
                <label>No. Urut</label>
                <input type="number" name="no_urut" id="input_no_urut" class="plain-input" min="1" placeholder="1">
            </div>

            <div class="form-row-2">
                <div class="field-group">
                    <label>Nama Ketua</label>
                    <input type="text" name="nama_ketua" id="input_nama_ketua" class="plain-input" placeholder="Nama calon ketua">
                </div>
                <div class="field-group">
                    <label>Kelas Ketua</label>
                    <input type="text" name="kelas_ketua" id="input_kelas_ketua" class="plain-input" placeholder="XII RPL 1">
                </div>
            </div>

            <div class="form-row-2">
                <div class="field-group">
                    <label>Nama Wakil</label>
                    <input type="text" name="nama_wakil" id="input_nama_wakil" class="plain-input" placeholder="Nama calon wakil">
                </div>
                <div class="field-group">
                    <label>Kelas Wakil</label>
                    <input type="text" name="kelas_wakil" id="input_kelas_wakil" class="plain-input" placeholder="XI TKJ 2">
                </div>
            </div>

            <div class="field-group">
                <label>Visi</label>
                <textarea name="visi" id="input_visi" class="plain-input" rows="3" placeholder="Tuliskan visi pasangan..."></textarea>
            </div>

            <div class="field-group">
                <label>Misi</label>
                <div id="wrapperMisi"></div>
                <a href="#" class="tambah-poin-link" onclick="event.preventDefault(); tambahPoin('wrapperMisi', 'misi')">+ Tambah Poin Misi</a>
            </div>

            <div class="field-group">
                <label>Program Kerja</label>
                <div id="wrapperProgramKerja"></div>
                <a href="#" class="tambah-poin-link" onclick="event.preventDefault(); tambahPoin('wrapperProgramKerja', 'program_kerja')">+ Tambah Poin Program Kerja</a>
            </div>

            <div class="field-group">
                <label>Foto Pasangan</label>
                <label for="inputFoto" class="dropzone" id="dropzoneFoto">
                    <div id="previewFotoWrapper">
                        <i class="fa-regular fa-image"></i>
                        <div class="dz-sub">Drag &amp; drop foto atau klik untuk upload</div>
                    </div>
                </label>
                <input type="file" name="foto" id="inputFoto" accept="image/*" style="display:none" onchange="previewFoto(this)">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" id="btnSubmitKandidat">Simpan Kandidat</button>
                <button type="button" class="btn-cancel" onclick="tutupModalKandidat()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL HAPUS KANDIDAT -->
<div class="modal-overlay" id="modalHapusKandidat">
    <div class="modal-box">
        <img id="hapusKandidatFoto" src="" alt="" class="modal-hapus-foto" style="display:none;">
        <h3 class="modal-title-danger" style="color:#1E293B;">Hapus Kandidat</h3>
        <p class="modal-desc">
            Yakin ingin menghapus pasangan kandidat:<br>
            <strong id="hapusKandidatNama" style="color:#2D6BA0;"></strong>
        </p>
        <div class="modal-warning-box">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>Tindakan ini tidak dapat dibatalkan.</span>
        </div>
        <form id="formHapusKandidat" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="submit" class="btn-danger-solid">Ya, Hapus</button>
                <button type="button" class="btn-cancel-outline" onclick="tutupModalHapusKandidat()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    const routeStore = "{{ route('admin.kandidat.store') }}";

    function buatBarisPoin(wrapperId, name, value = '') {
        const wrapper = document.getElementById(wrapperId);
        const row = document.createElement('div');
        row.className = 'poin-row';
        const placeholder = name === 'misi' ? 'Misi...' : 'Program kerja...';
        row.innerHTML = `
            <input type="text" name="${name}[]" class="plain-input" value="${value.replace(/"/g, '&quot;')}" placeholder="${placeholder}">
            <button type="button" class="poin-remove" onclick="this.parentElement.remove()">&times;</button>
        `;
        wrapper.appendChild(row);
    }

    function tambahPoin(wrapperId, name) {
        buatBarisPoin(wrapperId, name);
    }

    function resetFormKandidat() {
        document.getElementById('formKandidat').reset();
        document.getElementById('wrapperMisi').innerHTML = '';
        document.getElementById('wrapperProgramKerja').innerHTML = '';
        tambahPoin('wrapperMisi', 'misi');
        tambahPoin('wrapperProgramKerja', 'program_kerja');
        document.getElementById('previewFotoWrapper').innerHTML =
            '<i class="fa-regular fa-image"></i><div class="dz-sub">Drag &amp; drop foto atau klik untuk upload</div>';
    }

    function bukaModalTambah() {
        resetFormKandidat();
        document.getElementById('modalKandidatTitle').textContent = 'Tambah Kandidat';
        document.getElementById('formKandidat').action = routeStore;
        document.getElementById('methodOverride').value = 'POST';
        document.getElementById('btnSubmitKandidat').textContent = 'Simpan Kandidat';
        document.getElementById('modalKandidat').style.display = 'flex';
    }

    function bukaModalEdit(kandidat) {
        resetFormKandidat();
        document.getElementById('modalKandidatTitle').textContent = 'Edit Kandidat';
        document.getElementById('formKandidat').action = '/admin/kandidat/' + kandidat.id;
        document.getElementById('methodOverride').value = 'PUT';
        document.getElementById('btnSubmitKandidat').textContent = 'Perbarui Kandidat';

        document.getElementById('input_no_urut').value = kandidat.no_urut;
        document.getElementById('input_nama_ketua').value = kandidat.nama_ketua;
        document.getElementById('input_kelas_ketua').value = kandidat.kelas_ketua;
        document.getElementById('input_nama_wakil').value = kandidat.nama_wakil;
        document.getElementById('input_kelas_wakil').value = kandidat.kelas_wakil;
        document.getElementById('input_visi').value = kandidat.visi;

        document.getElementById('wrapperMisi').innerHTML = '';
        const misiList = kandidat.misi || [];
        if (misiList.length === 0) {
            tambahPoin('wrapperMisi', 'misi');
        } else {
            misiList.forEach(m => buatBarisPoin('wrapperMisi', 'misi', m));
        }

        document.getElementById('wrapperProgramKerja').innerHTML = '';
        const programList = kandidat.program_kerja || [];
        if (programList.length === 0) {
            tambahPoin('wrapperProgramKerja', 'program_kerja');
        } else {
            programList.forEach(p => buatBarisPoin('wrapperProgramKerja', 'program_kerja', p));
        }

        if (kandidat.foto) {
            document.getElementById('previewFotoWrapper').innerHTML =
                '<img src="/image/' + kandidat.foto + '" class="foto-preview-img">';
        }

        document.getElementById('modalKandidat').style.display = 'flex';
    }

    function tutupModalKandidat() {
        document.getElementById('modalKandidat').style.display = 'none';
    }

    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewFotoWrapper').innerHTML =
                    '<img src="' + e.target.result + '" class="foto-preview-img">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function bukaModalHapusKandidat(id, nama, foto) {
        document.getElementById('hapusKandidatNama').textContent = nama;
        document.getElementById('formHapusKandidat').action = '/admin/kandidat/' + id;

        const fotoEl = document.getElementById('hapusKandidatFoto');
        if (foto) {
            fotoEl.src = foto;
            fotoEl.style.display = 'block';
        } else {
            fotoEl.style.display = 'none';
        }

        document.getElementById('modalHapusKandidat').style.display = 'flex';
    }

    function tutupModalHapusKandidat() {
        document.getElementById('modalHapusKandidat').style.display = 'none';
    }
</script>
@endsection
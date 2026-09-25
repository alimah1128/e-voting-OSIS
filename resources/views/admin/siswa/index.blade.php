@extends('layouts.admin')

@section('title', 'Kelola Data Siswa')

@section('content')
<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>

    <div class="header-action">
        <div>
            <h2>Kelola Data Siswa</h2>
            <p>Manajemen akun siswa untuk E-Voting</p>
        </div>
        <div class="btn-group">
            <button type="button" class="btn-danger" onclick="bukaModalHapusSemua()">Hapus Semua</button>
            <button type="button" class="btn-primary" onclick="bukaModalTambahSiswa()">+ Tambah Siswa</button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-message alert-success" id="flashMessage">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert-message alert-warning" id="flashMessage">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('warning') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert-message alert-error" id="flashMessage">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    @if (!$periodeAktif)
        <div class="form-error-box" style="background:#FFFBEB; border-color:#FDE68A; color:#B45309;">
            Belum ada periode voting yang aktif, jadi kolom &amp; filter Status Voting belum bisa ditampilkan. Aktifkan periode dulu di menu Kelola Periode.
        </div>
    @endif

    <!-- Form Pencarian & Filter -->
    <form action="{{ route('admin.siswa.index') }}" method="GET" class="filter-form">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, username..." onchange="this.form.submit()">

        <select name="kelas" onchange="this.form.submit()">
            <option value="Semua">Semua Kelas</option>
            @foreach ($daftarKelas as $k)
                <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>

        @if ($periodeAktif)
            <select name="status_voting" onchange="this.form.submit()">
                <option value="Semua">Semua Status</option>
                <option value="sudah" {{ request('status_voting') == 'sudah' ? 'selected' : '' }}>Sudah Memilih</option>
                <option value="belum" {{ request('status_voting') == 'belum' ? 'selected' : '' }}>Belum Memilih</option>
            </select>
        @endif
    </form>

    <!-- Tabel Data Siswa -->
    <table class="table">
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jenis Kelamin</th>
                <th>Username</th>
                <th>Status Voting</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $siswa)
            <tr>
                <td>{{ $siswa->nis }}</td>
                <td><strong>{{ $siswa->nama }}</strong></td>
                <td>{{ $siswa->kelas }}</td>
                <td>{{ $siswa->jenis_kelamin }}</td>
                <td>{{ $siswa->username }}</td>
                <td>
                    @if (!$periodeAktif)
                        <span style="color:#94A3B8; font-size:12px;">—</span>
                    @elseif ($sudahVotingIds->contains($siswa->id))
                        <span class="badge-voting badge-sudah"><i class="fa-solid fa-circle-check"></i> Sudah</span>
                    @else
                        <span class="badge-voting badge-belum"><i class="fa-solid fa-circle-xmark"></i> Belum</span>
                    @endif
                </td>
                <td>
                    <a href="javascript:void(0)" class="btn-edit"
                       data-siswa="{{ json_encode($siswa) }}"
                       onclick="bukaModalEditSiswa(this)">Edit</a>
                    <button type="button" class="btn-delete"
                        data-id="{{ $siswa->id }}"
                        data-nama="{{ $siswa->nama }}"
                        onclick="bukaModalHapusSiswa(this)">Hapus</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Data siswa tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Laravel -->
    <div class="pagination-wrapper">
        <span>Menampilkan {{ $siswas->firstItem() ?? 0 }} dari {{ $siswas->total() }} siswa</span>
        {{ $siswas->links() }}
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div class="modal-overlay" id="modalTambahSiswa">
    <div class="modal-box modal-box-lg">
        <h3 class="modal-form-title">Tambah Siswa Baru</h3>
        <p class="form-subtitle" style="margin-top:-12px;">Tambahkan data akun siswa untuk E-Voting</p>

        @if ($errors->any() && old('_method') !== 'PUT')
            <div class="form-error-box">
                <strong>Ada kesalahan input:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- TAB SWITCH -->
        <div class="tab-switch">
            <button type="button" class="tab-btn active" id="tabBtnManual" onclick="gantiTab('manual')">
                <i class="fa-solid fa-pen"></i> Manual
            </button>
            <button type="button" class="tab-btn" id="tabBtnImport" onclick="gantiTab('import')">
                <i class="fa-solid fa-folder"></i> Import File
            </button>
        </div>

        <!-- PANEL: MANUAL -->
        <div class="tab-panel active" id="panelManual">
            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf

                <div class="field-group">
                    <label>NIS</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-id-card"></i>
                        <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Nomor Induk Siswa">
                    </div>
                </div>

                <div class="field-group">
                    <label>Nama Lengkap</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap siswa">
                    </div>
                </div>

                <div class="field-group">
                    <label>Kelas</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-school"></i>
                        <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: XI RPL 1">
                    </div>
                </div>

                <div class="field-group">
                    <label>Username</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-comment"></i>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Username login">
                    </div>
                </div>

                <div class="field-group">
                    <label>Password</label>
                    <div class="input-icon-wrapper input-icon-password-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" id="passwordTambah"
                            placeholder="Minimal 6 karakter" autocomplete="new-password" data-lpignore="true" data-1p-ignore data-form-type="other">
                        <button type="button" class="icon-toggle-password" onclick="togglePasswordField('passwordTambah', 'eyeTambah')">
                            <i class="fa-solid fa-eye" id="eyeTambah"></i>
                        </button>
                    </div>
                </div>

                <div class="field-group">
                    <label>Jenis Kelamin</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-venus-mars"></i>
                        <select name="jenis_kelamin">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Simpan</button>
                    <button type="button" class="btn-cancel" onclick="tutupModalTambahSiswa()">Batal</button>
                </div>
            </form>
        </div>

        <!-- PANEL: IMPORT FILE -->
        <div class="tab-panel" id="panelImport">
            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="fileImport" class="dropzone" id="dropzoneLabel">
                    <i class="fa-solid fa-folder"></i>
                    <div class="dz-title">Drag & drop file Excel/CSV di sini</div>
                    <div class="dz-sub">atau klik untuk memilih file</div>
                    <a href="{{ route('admin.siswa.template') }}" onclick="event.stopPropagation()">Download Template</a>
                    <div class="dz-filename" id="namaFileTerpilih"></div>
                </label>
                <input type="file" name="file" id="fileImport" accept=".xlsx,.xls,.csv" style="display:none" onchange="tampilkanNamaFile(this)">

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Simpan</button>
                    <button type="button" class="btn-cancel" onclick="tutupModalTambahSiswa()">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div class="modal-overlay" id="modalEditSiswa">
    <div class="modal-box modal-box-lg">
        <h3 class="modal-form-title">Edit Siswa</h3>
        <p class="form-subtitle" style="margin-top:-12px;">Perbarui data akun siswa</p>

        @if ($errors->any() && old('_method') === 'PUT')
            <div class="form-error-box">
                <strong>Ada kesalahan input:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="" method="POST" id="formEditSiswa">
            @csrf
            @method('PUT')

            <div class="field-group">
                <label>NIS</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-id-card"></i>
                    <input type="text" name="nis" id="editSiswaNis" placeholder="Nomor Induk Siswa">
                </div>
            </div>

            <div class="field-group">
                <label>Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="nama" id="editSiswaNama" placeholder="Nama lengkap siswa">
                </div>
            </div>

            <div class="field-group">
                <label>Kelas</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-school"></i>
                    <input type="text" name="kelas" id="editSiswaKelas" placeholder="Contoh: XI RPL 1">
                </div>
            </div>

            <div class="field-group">
                <label>Username</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-comment"></i>
                    <input type="text" name="username" id="editSiswaUsername" placeholder="Username login">
                </div>
            </div>

            <div class="field-group">
                <label>Password Baru</label>
                <div class="input-icon-wrapper input-icon-password-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" id="editSiswaPassword"
                        placeholder="Kosongkan jika tidak diganti" autocomplete="new-password"
                        data-lpignore="true" data-1p-ignore data-form-type="other">
                    <button type="button" class="icon-toggle-password"
                        onclick="togglePasswordField('editSiswaPassword', 'eyeEditSiswa')">
                        <i class="fa-solid fa-eye" id="eyeEditSiswa"></i>
                    </button>
                </div>
            </div>

            <div class="field-group">
                <label>Jenis Kelamin</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-venus-mars"></i>
                    <select name="jenis_kelamin" id="editSiswaJenisKelamin">
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Perbarui Siswa</button>
                <button type="button" class="btn-cancel" onclick="tutupModalEditSiswa()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Semua -->
<div class="modal-overlay" id="modalHapusSemua">
    <div class="modal-box">
        <div class="modal-icon-warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 class="modal-title-danger">Yakin ingin menghapus semua data siswa?</h3>
        <p class="modal-desc">
            Tindakan ini akan menghapus <strong>{{ $totalSiswa }} siswa</strong> secara permanen dan tidak dapat dibatalkan.
        </p>

        <div class="modal-warning-box">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>Seluruh data akun siswa termasuk username dan password akan terhapus. Siswa tidak akan bisa login lagi.</span>
        </div>

        <div class="modal-actions">
            <form action="{{ route('admin.siswa.deleteAll') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger-solid">Ya, Hapus Semua</button>
            </form>
            <button type="button" class="btn-cancel-outline" onclick="tutupModalHapusSemua()">Batal</button>
        </div>
    </div>
</div>

<!-- Form tersembunyi untuk hapus 1 siswa (action-nya diisi lewat JS) -->
<form action="" method="POST" id="formHapusSiswa" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<!-- Modal Konfirmasi Hapus Siswa -->
<div class="modal-overlay" id="modalHapusSiswa">
    <div class="modal-box">
        <div class="modal-icon-warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 class="modal-title-danger">Hapus Siswa?</h3>
        <p class="modal-desc">
            Yakin ingin menghapus siswa:<br>
            <strong id="hapusSiswaNama"></strong>
        </p>

        <div class="modal-warning-box">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>Data akun siswa ini termasuk username dan password akan terhapus permanen dan tidak dapat dibatalkan.</span>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-danger-solid" onclick="document.getElementById('formHapusSiswa').submit()">Ya, Hapus</button>
            <button type="button" class="btn-cancel-outline" onclick="tutupModalHapusSiswa()">Batal</button>
        </div>
    </div>
</div>

<style>
    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper input,
    .input-icon-wrapper select {
        padding-right: 40px;
    }

    .input-icon-password-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 12px 14px 12px 40px;
        transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }

    .input-icon-password-wrapper:focus-within {
        border-color: #38BDF8;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
    }

    .input-icon-password-wrapper input {
        flex: 1;
        min-width: 0;
        width: auto;
        border: none;
        outline: none;
        padding: 0;
        border-radius: 0;
        background: transparent;
        font-size: 13px;
        color: #334155;
    }

    .input-icon-password-wrapper input::placeholder {
        color: #94A3B8;
    }

    .icon-toggle-password {
        margin-left: auto;
        background: none;
        border: none;
        color: #94A3B8;
        cursor: pointer;
        padding: 0;
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    .input-icon-password-wrapper .icon-toggle-password i {
        position: static;
        left: auto;
        top: auto;
        transform: none;
        pointer-events: auto;
        color: inherit;
        font-size: inherit;
    }

    /* Badge Status Voting */
    .badge-voting {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-sudah {
        background: #DCFCE7;
        color: #16A34A;
    }

    .badge-belum {
        background: #FEF2F2;
        color: #DC2626;
    }

    .alert-message {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 18px;
        border: 1px solid transparent;
    }

    .alert-success {
        background: #DCFCE7;
        color: #16A34A;
        border-color: #BBF7D0;
    }

    .alert-warning {
        background: #FEF9C3;
        color: #A16207;
        border-color: #FDE68A;
    }

    .alert-error {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FECACA;
    }
</style>

<script>
    const editSiswaRouteTemplate = "{{ route('admin.siswa.update', 'GANTI_ID') }}";
    const destroySiswaRouteTemplate = "{{ route('admin.siswa.destroy', 'GANTI_ID') }}";

    function bukaModalHapusSemua() {
        document.getElementById('modalHapusSemua').style.display = 'flex';
    }
    function tutupModalHapusSemua() {
        document.getElementById('modalHapusSemua').style.display = 'none';
    }

    function bukaModalTambahSiswa() {
        document.getElementById('modalTambahSiswa').style.display = 'flex';
    }
    function tutupModalTambahSiswa() {
        document.getElementById('modalTambahSiswa').style.display = 'none';
    }

    function bukaModalEditSiswa(el) {
        const siswa = JSON.parse(el.dataset.siswa);

        document.getElementById('editSiswaNis').value = siswa.nis;
        document.getElementById('editSiswaNama').value = siswa.nama;
        document.getElementById('editSiswaKelas').value = siswa.kelas;
        document.getElementById('editSiswaJenisKelamin').value = siswa.jenis_kelamin;
        document.getElementById('editSiswaUsername').value = siswa.username;
        document.getElementById('editSiswaPassword').value = '';
        document.getElementById('formEditSiswa').action = editSiswaRouteTemplate.replace('GANTI_ID', siswa.id);
        document.getElementById('modalEditSiswa').style.display = 'flex';
    }

    function tutupModalEditSiswa() {
        document.getElementById('modalEditSiswa').style.display = 'none';
    }

    function bukaModalHapusSiswa(el) {
        const id = el.dataset.id;
        const nama = el.dataset.nama;

        document.getElementById('hapusSiswaNama').textContent = nama;
        document.getElementById('formHapusSiswa').action = destroySiswaRouteTemplate.replace('GANTI_ID', id);
        document.getElementById('modalHapusSiswa').style.display = 'flex';
    }

    function tutupModalHapusSiswa() {
        document.getElementById('modalHapusSiswa').style.display = 'none';
    }

    function gantiTab(tab) {
        document.getElementById('panelManual').classList.toggle('active', tab === 'manual');
        document.getElementById('panelImport').classList.toggle('active', tab === 'import');
        document.getElementById('tabBtnManual').classList.toggle('active', tab === 'manual');
        document.getElementById('tabBtnImport').classList.toggle('active', tab === 'import');
    }

    function tampilkanNamaFile(input) {
        const nama = input.files.length ? input.files[0].name : '';
        document.getElementById('namaFileTerpilih').textContent = nama ? 'File dipilih: ' + nama : '';
    }

    function togglePasswordField(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    @if ($errors->any() && old('_method') !== 'PUT')
        document.addEventListener('DOMContentLoaded', function () {
            bukaModalTambahSiswa();
        });
    @endif

    document.addEventListener('DOMContentLoaded', function () {
        const flash = document.getElementById('flashMessage');
        if (flash) {
            setTimeout(() => {
                flash.style.transition = 'opacity 0.4s ease';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 400);
            }, 4000);
        }
    });
</script>
@endsection

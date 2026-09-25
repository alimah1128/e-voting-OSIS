@extends('layouts.admin')

@section('title', 'Tambah Siswa')

@section('content')
<div class="container">
    <a href="{{ route('admin.siswa.index') }}" class="btn-back">← Kembali ke Kelola Data Siswa</a>

    <div class="header-action">
        <div>
            <h2>Tambah Siswa Baru</h2>
            <p>Tambahkan data akun siswa untuk E-Voting</p>
        </div>
    </div>

    <div class="form-card">
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
                        <select name="kelas">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach (['X RPL 1', 'X RPL 2', 'XI RPL 1', 'XI RPL 2', 'XII RPL 1', 'XII RPL 2', 'XII RPL 3'] as $kelas)
                                <option value="{{ $kelas }}" {{ old('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                            @endforeach
                        </select>
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
                        <input type="text" name="password" id="passwordTambah" class="password-mask"
                            placeholder="Minimal 6 karakter" autocomplete="off" data-lpignore="true" data-1p-ignore data-form-type="other">
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
                    <a href="{{ route('admin.siswa.index') }}" class="btn-cancel">Batal</a>
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
                    <a href="{{ route('admin.siswa.index') }}" class="btn-cancel">Batal</a>
                </div>
            </form>
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

    /* Menyamarkan tampilan teks jadi titik-titik tanpa pakai type="password" bawaan browser,
       supaya Chrome tidak mendeteksinya sebagai field password dan tidak muncul
       "Pengelola Sandi Google" / icon mata bawaan Chrome yang numpuk. */
    .password-mask {
        -webkit-text-security: disc;
    }

    .input-icon-password-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
    }

    .input-icon-password-wrapper input {
        flex-grow: 1;
        border: none;
        outline: none;
        padding-right: 0;
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

    /* FIX: netralkan aturan global ".input-icon-wrapper i" yang ikut "menculik"
       ikon mata (di dalam tombol .icon-toggle-password) jadi position:absolute
       dan menumpuknya di atas ikon gembok. Selector di bawah ini sengaja dibuat
       lebih spesifik (2 class + tag) supaya menang lawan CSS global di layout. */
    .input-icon-password-wrapper .icon-toggle-password i {
        position: static;
        left: auto;
        top: auto;
        transform: none;
        pointer-events: auto;
        color: inherit;
        font-size: inherit;
    }
</style>

<script>
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

    // Toggle tampil/sembunyi password dengan cara ganti class .password-mask,
    // bukan ganti type="password"/"text", supaya Chrome tetap tidak mendeteksinya
    // sebagai field password.
    function togglePasswordField(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        input.classList.toggle('password-mask');

        if (input.classList.contains('password-mask')) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>
@endsection
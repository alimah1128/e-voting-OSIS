@extends('layouts.admin')

@section('title', 'Edit Siswa')

@section('content')
<div class="container">
    <a href="{{ route('admin.siswa.index') }}" class="btn-back">← Kembali ke Kelola Data Siswa</a>

    <div class="header-action">
        <div>
            <h2>Edit Siswa</h2>
            <p>Perbarui data akun siswa</p>
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

        <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label>NIS</label>
                    <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}">
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" placeholder="Contoh: XI RPL 1">
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username', $siswa->username) }}">
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="passwordEdit"
                            placeholder="Kosongkan jika tidak diganti"
                            style="width:100%; padding-right:40px;"
                            autocomplete="new-password">
                        <button type="button" onclick="togglePasswordEdit()" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94A3B8; cursor:pointer; padding:0; font-size:14px; z-index:2;">
                            <i class="fa-regular fa-eye" id="eyeEdit"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Perbarui Siswa</button>
                <a href="{{ route('admin.siswa.index') }}" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>

<style>
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }
</style>

<script>
    function togglePasswordEdit() {
        const input = document.getElementById('passwordEdit');
        const icon = document.getElementById('eyeEdit');
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
</script>
@endsection
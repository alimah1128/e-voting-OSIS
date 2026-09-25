@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>

    <div class="header-action">
        <div>
            <h2>Profil Admin</h2>
            <p>Kelola informasi akun admin kamu</p>
        </div>
    </div>

    <div class="form-card">
        @if (session('success'))
            <div class="modal-warning-box" style="background:#F0FDF4; border-color:#BBF7D0; color:#16A34A;">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

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

        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="field-group" style="text-align:center;">
                <label for="inputFotoProfil" class="dropzone" id="dropzoneFotoProfil" style="cursor:pointer;">
                    <div id="previewFotoProfilWrapper">
                        @if ($admin->foto)
                            <img src="{{ asset('image/' . $admin->foto) }}" class="foto-preview-img" style="border-radius:50%; width:90px; height:90px; object-fit:cover;">
                        @else
                            <i class="fa-regular fa-user" style="font-size:36px;"></i>
                            <div class="dz-sub">Klik untuk upload foto profil</div>
                        @endif
                    </div>
                </label>
                <input type="file" name="foto" id="inputFotoProfil" accept="image/*" style="display:none" onchange="previewFotoProfil(this)">
            </div>

            <div class="field-group">
                <label>Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $admin->nama_lengkap) }}" placeholder="Nama lengkap admin">
                </div>
            </div>

            <div class="field-group">
                <label>Username</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-at"></i>
                    <input type="text" name="username" value="{{ old('username', $admin->username) }}" placeholder="Username login">
                </div>
            </div>

            <div class="field-group">
                <label>Password Baru</label>
                <div class="input-icon-wrapper input-icon-password-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" id="passwordProfil"
                        placeholder="Kosongkan jika tidak diganti" autocomplete="new-password"
                        data-lpignore="true" data-1p-ignore data-form-type="other">
                    <button type="button" class="icon-toggle-password" onclick="togglePasswordField('passwordProfil', 'eyeProfil')">
                        <i class="fa-solid fa-eye" id="eyeProfil"></i>
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Batal</a>
            </div>
        </form>
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
</style>

<script>
    function previewFotoProfil(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewFotoProfilWrapper').innerHTML =
                    '<img src="' + e.target.result + '" class="foto-preview-img" style="border-radius:50%; width:90px; height:90px; object-fit:cover;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
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
</script>
@endsection
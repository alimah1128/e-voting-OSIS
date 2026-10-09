@extends('admin.layouts.cms')
@section('title', 'Pengaturan Beranda')

@section('content')
    <h2 class="page-title">Pengaturan Beranda</h2>
    <p class="page-sub">Teks dan foto di sini langsung tampil di halaman utama website.</p>

    <form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <h3 style="margin-bottom:14px;color:var(--navy)">Bagian Atas (Hero)</h3>
            <div class="fg"><label>Judul</label>
                <input type="text" name="hero_judul" value="{{ old('hero_judul', $data['hero_judul'] ?? '') }}" placeholder="OSIS SMK Pertiwi Kuningan"></div>
            <div class="fg"><label>Tagline</label>
                <input type="text" name="hero_tagline" value="{{ old('hero_tagline', $data['hero_tagline'] ?? '') }}" placeholder="Suaramu. Masa depan sekolah kita."></div>
            <div class="fg"><label>Foto Utama (foto OSIS bersama)</label>
                @if(!empty($data['hero_foto']))
                    <img src="{{ asset('storage/'.$data['hero_foto']) }}" alt="" style="width:100%;max-width:360px;border-radius:12px;margin-bottom:8px;display:block">
                @endif
                <input type="file" name="hero_foto" accept="image/*">
                <br><small>Disarankan foto landscape, lebar minimal 1600 px, maks 4 MB. Kosongkan jika tidak ingin mengganti.</small></div>
        </div>

        <div class="card">
            <h3 style="margin-bottom:14px;color:var(--navy)">Tentang OSIS</h3>
            <div class="fg"><label>Foto Tentang OSIS</label>
                @if(!empty($data['tentang_foto']))
                    <img src="{{ asset('storage/'.$data['tentang_foto']) }}" alt="" style="width:100%;max-width:280px;border-radius:12px;margin-bottom:8px;display:block">
                @endif
                <input type="file" name="tentang_foto" accept="image/*">
                <br><small>Maks 4 MB. Kalau kosong, yang tampil adalah logo OSIS.</small></div>
            <div class="fg"><label>Profil OSIS</label>
                <textarea name="tentang_profil">{{ old('tentang_profil', $data['tentang_profil'] ?? '') }}</textarea></div>
            <div class="fg"><label>Visi</label>
                <textarea name="visi">{{ old('visi', $data['visi'] ?? '') }}</textarea></div>
            <div class="fg"><label>Misi</label>
                <textarea name="misi">{{ old('misi', $data['misi'] ?? '') }}</textarea>
                <small>Satu baris = satu poin misi.</small></div>
        </div>

        <div class="card">
            <h3 style="margin-bottom:14px;color:var(--navy)">Kontak</h3>
            <div class="fg"><label>Alamat</label>
                <input type="text" name="kontak_alamat" value="{{ old('kontak_alamat', $data['kontak_alamat'] ?? '') }}"></div>
            <div class="row2">
                <div class="fg"><label>Email</label>
                    <input type="email" name="kontak_email" value="{{ old('kontak_email', $data['kontak_email'] ?? '') }}"></div>
                <div class="fg"><label>Instagram</label>
                    <input type="text" name="kontak_instagram" value="{{ old('kontak_instagram', $data['kontak_instagram'] ?? '') }}" placeholder="@osis_smkpertiwi"></div>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
    </form>
@endsection
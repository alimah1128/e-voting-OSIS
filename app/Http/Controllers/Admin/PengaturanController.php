@extends('admin.layouts.cms')
@section('title', 'Pengaturan Beranda')

@section('content')
    <h2 class="page-title">Pengaturan Beranda</h2>
    <p class="page-sub">Teks dan foto di sini langsung tampil di halaman utama website.</p>

    <form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <h3 style="margin-bottom:14px;color:var(--navy)">Bagian Atas (Hero)</h3>

            <div class="fg"><label>Badge Slogan</label>
                <input type="text" name="hero_badge" value="{{ old('hero_badge', $data['hero_badge'] ?? '') }}" placeholder="Bergerak • Berkarya • Menginspirasi">
                <small>Tulisan kecil di atas judul.</small></div>

            <div class="row2">
                <div class="fg"><label>Judul Baris 1 (putih)</label>
                    <input type="text" name="hero_judul" value="{{ old('hero_judul', $data['hero_judul'] ?? '') }}" placeholder="OSIS SMK Pertiwi"></div>
                <div class="fg"><label>Judul Baris 2 (biru muda)</label>
                    <input type="text" name="hero_judul2" value="{{ old('hero_judul2', $data['hero_judul2'] ?? '') }}" placeholder="Kuningan"></div>
            </div>

            <div class="fg"><label>Deskripsi</label>
                <textarea name="hero_tagline" style="min-height:90px">{{ old('hero_tagline', $data['hero_tagline'] ?? '') }}</textarea>
                <small>Kalimat di bawah judul.</small></div>

            <div class="row2">
                <div class="fg"><label>Teks Tombol 1</label>
                    <input type="text" name="hero_btn1_teks" value="{{ old('hero_btn1_teks', $data['hero_btn1_teks'] ?? '') }}" placeholder="Tentang OSIS"></div>
                <div class="fg"><label>Teks Tombol 2</label>
                    <input type="text" name="hero_btn2_teks" value="{{ old('hero_btn2_teks', $data['hero_btn2_teks'] ?? '') }}" placeholder="Lihat Acara"></div>
            </div>

            <div class="fg"><label>Foto Latar Hero (foto OSIS bersama)</label>
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
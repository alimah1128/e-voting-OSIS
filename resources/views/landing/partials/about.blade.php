{{--
    Pemakaian:
    @include('landing.partials.about', ['p' => $p, 'ringkas' => true])   -> beranda (teks dipendekkan + tombol ke /tentang)
    @include('landing.partials.about', ['p' => $p, 'ringkas' => false])  -> halaman tentang (teks penuh)
--}}
@once
@push('styles')
<style>
    .about { display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:center; }
    .about-photo { position:relative; }
    .about-photo .frame { background:#fff; padding:10px; border-radius:20px; box-shadow:0 14px 34px rgba(2,132,199,.18); }
    .about-photo .frame img { width:100%; aspect-ratio:4/3; object-fit:cover; object-position:center 30%; border-radius:14px; display:block; }
    .about-photo .frame .logo-only { aspect-ratio:4/3; display:flex; align-items:center; justify-content:center; background:#e0f2fe; border-radius:14px; }
    .about-photo .frame .logo-only img { width:50%; aspect-ratio:auto; object-fit:contain; border-radius:0; }

    /* ← DIPERBAIKI: badge logo = lingkaran putih polos, logo di dalamnya TIDAK dipotong
       (ekor pita "SMK Pertiwi Kuningan" menjorok ke samping, jadi gambar tidak boleh di-clip bulat) */
    .about-photo .badge { position:absolute; top:-26px; left:-26px; width:96px; height:96px; border-radius:50%;
            background:#fff; border:4px solid #38bdf8; display:flex; align-items:center; justify-content:center;
            box-shadow:0 10px 24px rgba(2,132,199,.35); }
    .about-photo .badge img { width:80%; height:80%; object-fit:contain; display:block;
            background:none; border-radius:0; padding:0; }

    .about-text h2 { font-size:clamp(24px,4vw,34px); color:var(--navy); margin-bottom:14px; line-height:1.25; }
    .about-text p { color:#64748b; line-height:1.8; font-size:15px; white-space:pre-line; margin-bottom:22px; }
    @media (max-width:820px){
        .about { grid-template-columns:1fr; gap:36px; }
        /* HP: foto dibuat sedikit lebih pendek supaya tidak terlalu besar */
        .about-photo .frame img { aspect-ratio:16/11; }
        .about-photo .badge { left:-8px; top:-20px; width:78px; height:78px; }
    }
    @media (max-width:560px){
        .about { gap:26px; }
        .about-photo .frame { padding:6px; border-radius:16px; }
        .about-photo .frame img { aspect-ratio:16/9; border-radius:11px; }
        .about-photo .frame .logo-only { aspect-ratio:16/9; border-radius:11px; }
        .about-photo .badge { left:-6px; top:-16px; width:62px; height:62px; border-width:3px; }
        .about-text h2 { font-size:21px; margin-bottom:10px; }
        .about-text p { font-size:13.5px; line-height:1.7; margin-bottom:16px; }
    }
</style>
@endpush
@endonce

@php
    $profil = $p['tentang_profil'] ?? 'Profil OSIS belum diisi.';
    if (!empty($ringkas)) { $profil = \Illuminate\Support\Str::limit($profil, 300); }
@endphp

<div class="about">
    <div class="about-photo">
        <div class="badge"><img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS"></div>
        <div class="frame">
            @if(!empty($p['tentang_foto']))
                <img src="{{ asset('storage/'.$p['tentang_foto']) }}" alt="Tentang OSIS">
            @else
                <div class="logo-only"><img src="{{ asset('image/logo-osis.png') }}" alt="Logo OSIS"></div>
            @endif
        </div>
    </div>
    <div class="about-text">
        <h2>Selamat Datang di OSIS SMK Pertiwi Kuningan</h2>
        <p>{{ $profil }}</p>
        @if(!empty($ringkas))
            <a href="{{ route('tentang') }}" class="btn">Selengkapnya tentang OSIS</a>
        @else
            <a href="{{ route('program') }}" class="btn">Lihat Program Kerja</a>
        @endif
    </div>
</div>
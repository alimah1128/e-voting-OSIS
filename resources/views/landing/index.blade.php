@extends('landing.layout')
@section('title', ($p['hero_judul'] ?? 'OSIS SMK Pertiwi Kuningan'))

@push('styles')
<style>
    section { padding:56px 0; }

    /* ===== HERO ===== */
    .hero { position:relative; min-height:92vh; display:flex; align-items:center; color:#fff;
            border-radius:0 0 44px 44px; overflow:hidden;
            background:linear-gradient(135deg,#0284c7,#38bdf8); background-size:cover; background-position:center; }
    .hero::before { content:''; position:absolute; inset:0;
            background:linear-gradient(90deg, rgba(7,89,133,.82) 0%, rgba(7,89,133,.45) 55%, rgba(7,89,133,.12) 100%); }
    .hero-in { position:relative; z-index:2; max-width:1100px; width:100%; margin:0 auto; padding:120px 5% 110px; }
    .hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.18); backdrop-filter:blur(6px);
                  padding:6px 14px 6px 8px; border-radius:999px; font-size:13px; font-weight:500; margin-bottom:18px; }
    .hero-badge img { height:26px; background:#fff; border-radius:50%; padding:2px; }
    .hero h1 { font-size:clamp(32px,6vw,58px); font-weight:800; line-height:1.15; max-width:640px; text-shadow:0 4px 20px rgba(0,0,0,.25); }
    .hero .tag { margin:16px 0 28px; font-size:clamp(14px,2vw,18px); max-width:520px; opacity:.95; }
    .hero-btns { display:flex; gap:12px; flex-wrap:wrap; }
    .btn { display:inline-block; padding:13px 30px; border-radius:999px; font-weight:600; border:none; cursor:pointer;
           font-family:inherit; font-size:15px; color:#fff; background:linear-gradient(135deg,#00b4db,#0083b0);
           box-shadow:0 8px 20px rgba(0,131,176,.3); transition:.2s; text-decoration:none; }
    .btn:hover { transform:translateY(-2px); }
    .btn-ghost { background:transparent; border:2px solid rgba(255,255,255,.85); box-shadow:none; }
    .btn-ghost:hover { background:rgba(255,255,255,.15); }

    /* ===== BAR INFO (menumpuk di bawah hero) ===== */
    .infobar { position:relative; z-index:5; max-width:960px; margin:-58px auto 0; padding:14px;
               background:#bfe6fa; border-radius:999px; box-shadow:0 14px 34px rgba(2,132,199,.2);
               display:grid; grid-template-columns:repeat(3,1fr) auto; gap:12px; align-items:center; }
    .pill { background:#fff; border-radius:999px; padding:10px 20px; text-align:center; }
    .pill b { display:block; font-size:22px; color:var(--sky-dark); line-height:1.2; }
    .pill span { font-size:12px; color:#64748b; }
    .infobar .btn { padding:15px 28px; }
    .infobar-wrap { padding:0 5%; }

    /* ===== TENTANG ===== */
    .about { display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:center; }
    .about-photo { position:relative; }
    .about-photo .frame { background:#fff; padding:10px; border-radius:20px; box-shadow:0 14px 34px rgba(2,132,199,.18); }
    .about-photo .frame img { width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:14px; display:block; }
    .about-photo .frame .logo-only { aspect-ratio:4/3; display:flex; align-items:center; justify-content:center; background:#e0f2fe; border-radius:14px; }
    .about-photo .frame .logo-only img { width:50%; aspect-ratio:auto; object-fit:contain; border-radius:0; }
    .about-photo .badge { position:absolute; top:-22px; left:-22px; width:84px; height:84px; border-radius:50%;
            background:linear-gradient(135deg,#00b4db,#0083b0); display:flex; align-items:center; justify-content:center;
            box-shadow:0 10px 24px rgba(0,131,176,.4); border:4px solid #fff; }
    .about-photo .badge img { width:54px; background:#fff; border-radius:50%; padding:3px; }
    .about-text h2 { font-size:clamp(24px,4vw,34px); color:var(--navy); margin-bottom:14px; }
    .about-text p { color:#64748b; line-height:1.8; font-size:15px; white-space:pre-line; margin-bottom:22px; }

    h2.title { text-align:center; color:var(--navy); margin-bottom:10px; font-size:clamp(22px,4vw,30px); }
    p.title-sub { text-align:center; color:#64748b; font-size:14px; margin-bottom:28px; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:22px; }
    .card h3 { color:var(--navy); margin-bottom:8px; font-size:17px; }
    .card p, .card li { color:#64748b; font-size:14px; line-height:1.7; white-space:pre-line; }
    .card ul { padding-left:18px; }
    .card.hover { transition:.25s; } .card.hover:hover { transform:translateY(-6px); box-shadow:0 16px 34px rgba(2,132,199,.18); }
    .ikon { font-size:34px; margin-bottom:8px; }
    .pic { width:100%; height:160px; object-fit:cover; border-radius:14px; margin-bottom:12px; }

    /* ===== KARTU ACARA / ARTIKEL ===== */
    .band { background:#e6f4fc; border-radius:40px; }
    .art { background:#fff; border-radius:24px; overflow:hidden; box-shadow:0 10px 26px rgba(2,132,199,.1);
           display:flex; flex-direction:column; transition:.25s; text-decoration:none; color:inherit; }
    .art:hover { transform:translateY(-6px); box-shadow:0 18px 38px rgba(2,132,199,.2); }
    .art-img { position:relative; height:180px; background:linear-gradient(135deg,#bae6fd,#7dd3fc);
               display:flex; align-items:center; justify-content:center; font-size:48px; }
    .art-img img { width:100%; height:100%; object-fit:cover; display:block; }
    .art-tag { position:absolute; top:12px; left:12px; background:#fff; color:var(--sky-dark); font-size:11px; font-weight:700;
               padding:5px 12px; border-radius:999px; box-shadow:0 4px 10px rgba(0,0,0,.1); }
    .art-body { padding:20px; display:flex; flex-direction:column; flex:1; }
    .art-date { font-size:12px; color:#94a3b8; margin-bottom:6px; }
    .art-body h3 { color:var(--navy); font-size:17px; margin-bottom:8px; line-height:1.4; }
    .art-body p { color:#64748b; font-size:13.5px; line-height:1.7; flex:1; margin-bottom:16px; }
    .art-btn { align-self:flex-start; background:linear-gradient(135deg,#00b4db,#0083b0); color:#fff; font-size:13px; font-weight:600;
               padding:9px 20px; border-radius:999px; }
    .kosong { text-align:center; color:#94a3b8; padding:20px; }

    @media (max-width:820px){
        .about { grid-template-columns:1fr; gap:36px; }
        .infobar { border-radius:28px; grid-template-columns:1fr 1fr; margin-top:-40px; }
        .infobar .btn { grid-column:1/-1; }
        .hero { min-height:80vh; border-radius:0 0 30px 30px; }
        .hero-in { padding:110px 6% 90px; }
        .about-photo .badge { left:-8px; top:-18px; width:70px; height:70px; }
    }
</style>
@endpush

@section('content')

    {{-- ===== HERO ===== --}}
    <header id="beranda" class="hero"
        @if(!empty($p['hero_foto'])) style="background-image:url('{{ asset('storage/'.$p['hero_foto']) }}')" @endif>
        <div class="hero-in">
            <div class="hero-badge">
                <img src="{{ asset('image/logo-osis.png') }}" alt="">
                SMK Pertiwi Kuningan
            </div>
            <h1>{{ $p['hero_judul'] ?? 'OSIS SMK Pertiwi Kuningan' }}</h1>
            <p class="tag">{{ $p['hero_tagline'] ?? '' }}</p>
            <div class="hero-btns">
                <a href="#tentang" class="btn">Tentang OSIS</a>
                <a href="#acara" class="btn btn-ghost">Lihat Acara</a>
            </div>
        </div>
    </header>

    {{-- ===== BAR INFO ===== --}}
    <div class="infobar-wrap">
        <div class="infobar">
            <div class="pill"><b>{{ number_format($jumlahSiswa) }}</b><span>Siswa</span></div>
            <div class="pill"><b>{{ $programs->count() }}</b><span>Program Kerja</span></div>
            <div class="pill"><b>{{ $jumlahAcara }}</b><span>Acara</span></div>
            <button class="btn" onclick="toggleModal(true)">Pemilihan</button>
        </div>
    </div>

    <div class="container">

        {{-- ===== TENTANG ===== --}}
        <section id="tentang">
            <div class="about">
                <div class="about-photo">
                    <div class="badge"><img src="{{ asset('image/logo-osis.png') }}" alt=""></div>
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
                    <p>{{ $p['tentang_profil'] ?? 'Profil OSIS belum diisi.' }}</p>
                    <a href="#program" class="btn">Lihat Program Kerja</a>
                </div>
            </div>
        </section>

        <section>
            <div class="grid">
                <div class="card hover"><h3>🎯 Visi</h3><p>{{ $p['visi'] ?? 'Belum diisi.' }}</p></div>
                <div class="card hover"><h3>🚀 Misi</h3>
                    @if($misi->count()) <ul>@foreach($misi as $m)<li>{{ $m }}</li>@endforeach</ul>
                    @else <p>Belum diisi.</p> @endif
                </div>
            </div>
        </section>

        {{-- ===== PROGRAM KERJA ===== --}}
        <section id="program">
            <h2 class="title">Program Kerja</h2>
            <p class="title-sub">Kegiatan yang kami rencanakan untuk sekolah.</p>
            @if($programs->count())
            <div class="grid">
                @foreach($programs as $pr)
                    <div class="card hover">
                        @if($pr->foto) <img class="pic" src="{{ asset('storage/'.$pr->foto) }}" alt="{{ $pr->judul }}">
                        @else <div class="ikon">{{ $pr->ikon ?: '📌' }}</div> @endif
                        <h3>{{ $pr->judul }}</h3>
                        <p>{{ $pr->deskripsi }}</p>
                    </div>
                @endforeach
            </div>
            @else <p class="kosong">Belum ada program kerja.</p> @endif
        </section>

    </div>

    {{-- ===== ACARA (kartu artikel) ===== --}}
    <div class="container">
        <section id="acara" class="band" style="padding:56px 4%;">
            <h2 class="title">Acara &amp; Kegiatan</h2>
            <p class="title-sub">Klik kartu untuk membaca cerita lengkap acaranya.</p>

            @if($acaras->count())
            <div class="grid">
                @foreach($acaras as $a)
                    <a href="{{ route('acara.show', $a->id) }}" class="art">
                        <div class="art-img">
                            @if($a->foto) <img src="{{ asset('storage/'.$a->foto) }}" alt="{{ $a->judul }}"> @else 📅 @endif
                            <span class="art-tag">{{ $a->tanggal->isFuture() || $a->tanggal->isToday() ? 'Akan Datang' : 'Selesai' }}</span>
                        </div>
                        <div class="art-body">
                            <div class="art-date">{{ $a->tanggal->translatedFormat('d F Y') }}@if($a->lokasi) · {{ $a->lokasi }}@endif</div>
                            <h3>{{ $a->judul }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($a->deskripsi), 100) }}</p>
                            <span class="art-btn">Baca Selengkapnya →</span>
                        </div>
                    </a>
                @endforeach
            </div>
            @else
                <p class="kosong">Belum ada acara.</p>
            @endif
        </section>
    </div>

    <div class="container">
        {{-- ===== KONTAK ===== --}}
        <section id="kontak">
            <h2 class="title">Kontak</h2>
            <div class="card" style="text-align:center">
                <p>📍 {{ $p['kontak_alamat'] ?? '-' }}</p>
                <p>✉️ {{ $p['kontak_email'] ?? '-' }}</p>
                <p>📷 {{ $p['kontak_instagram'] ?? '-' }}</p>
            </div>
        </section>
    </div>

@endsection
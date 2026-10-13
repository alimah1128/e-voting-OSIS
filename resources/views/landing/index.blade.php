@extends('landing.layout')
@section('title', ($p['hero_judul'] ?? 'OSIS SMK Pertiwi Kuningan'))

@push('styles')
<style>
    /* ===== HERO ===== */
    .hero { position:relative; min-height:100vh; min-height:100svh; display:flex; align-items:center; color:#fff;
            border-radius:0 0 36px 36px; overflow:hidden;
            background:linear-gradient(135deg,#075985,#0284c7); background-size:cover; background-position:center; }
    .hero::before { content:''; position:absolute; inset:0;
            background:linear-gradient(90deg, rgba(3,40,84,.88) 0%, rgba(7,89,133,.68) 55%, rgba(7,89,133,.45) 100%); }
    .hero-in { position:relative; z-index:2; max-width:1240px; width:100%; margin:0 auto; padding:130px 5% 90px; }
    .hero-badge { display:inline-flex; align-items:center; gap:10px; background:rgba(255,255,255,.14);
                  border:1px solid rgba(255,255,255,.3); backdrop-filter:blur(6px);
                  padding:8px 18px; border-radius:999px; font-size:13px; font-weight:600; margin-bottom:22px; }
    .hero-badge i { width:9px; height:9px; border-radius:50%; background:#38bdf8; display:block; flex-shrink:0; }
    .hero h1 { font-size:clamp(34px,7vw,68px); font-weight:700; line-height:1.12; max-width:760px; }
    .hero h1 .l2 { display:block; color:#7dd3fc; }
    .hero .tag { margin-top:22px; font-size:clamp(14px,2vw,17px); line-height:1.8; max-width:540px; opacity:.95; }
    /* jarak tombol dari teks di atasnya selalu ada, walau badge/tagline kosong */
    .hero-btns { display:flex; gap:12px; flex-wrap:wrap; margin-top:34px; }

    /* ===== GALERI ===== */
    .galeri { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
    .galeri figure { position:relative; border-radius:18px; overflow:hidden; aspect-ratio:4/3; background:#bae6fd;
                     box-shadow:0 8px 22px rgba(2,132,199,.12); }
    .galeri img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .4s; }
    .galeri figure:hover img { transform:scale(1.06); }
    .galeri figcaption { position:absolute; left:0; right:0; bottom:0; padding:22px 12px 10px; color:#fff; font-size:12.5px; line-height:1.4;
                         background:linear-gradient(transparent, rgba(3,40,84,.75)); }
    @media (max-width:900px){ .galeri { grid-template-columns:repeat(2,1fr); gap:10px; } }

    /* ===== AJAKAN PEMILIHAN ===== */
    .cta-band { position:relative; overflow:hidden; border-radius:32px; padding:44px 40px; color:#fff;
                background:linear-gradient(135deg,var(--deep),var(--sky-dark));
                display:flex; align-items:center; justify-content:space-between; gap:24px; flex-wrap:wrap; }
    .cta-band::after { content:''; position:absolute; width:260px; height:260px; border-radius:50%;
                right:-60px; top:-90px; background:rgba(125,211,252,.18); }
    .cta-band > * { position:relative; z-index:1; }
    .cta-band h2 { font-size:clamp(20px,3.5vw,28px); margin-bottom:6px; }
    .cta-band p { opacity:.9; font-size:14.5px; line-height:1.7; max-width:520px; }

    @media (max-width:820px){
        .hero { border-radius:0 0 26px 26px; }
        .hero-in { padding:110px 6% 70px; }
        .hero-btns .btn { flex:1 1 auto; text-align:center; }
        .cta-band { padding:32px 24px; }
        .cta-band .btn { width:100%; text-align:center; }
    }
</style>
@endpush

@section('content')

    @php
        $j1 = $p['hero_judul']  ?? '';
        $j2 = $p['hero_judul2'] ?? '';
        if ($j1 === '' && $j2 === '') { $j1 = 'OSIS SMK Pertiwi'; $j2 = 'Kuningan'; }
    @endphp

    {{-- ===== HERO ===== --}}
    <header class="hero"
        @if(!empty($p['hero_foto'])) style="background-image:url('{{ asset('storage/'.$p['hero_foto']) }}')" @endif>
        <div class="hero-in">
            @if(!empty($p['hero_badge']))
                <div class="hero-badge"><i></i>{{ $p['hero_badge'] }}</div>
            @endif

            <h1>
                {{ $j1 }}
                @if($j2 !== '') <span class="l2">{{ $j2 }}</span> @endif
            </h1>

            @if(!empty($p['hero_tagline']))
                <p class="tag">{{ $p['hero_tagline'] }}</p>
            @endif

            <div class="hero-btns">
                <a href="{{ route('tentang') }}" class="btn">{{ $p['hero_btn1_teks'] ?? 'Tentang OSIS' }} →</a>
                <a href="{{ route('acara.index') }}" class="btn btn-ghost">{{ $p['hero_btn2_teks'] ?? 'Lihat Acara' }}</a>
            </div>
        </div>
    </header>

    {{-- ===== SAMBUTAN SINGKAT ===== --}}
    <div class="container">
        <section>
            @include('landing.partials.about', ['p' => $p, 'ringkas' => true])
        </section>
    </div>

    {{-- ===== PROGRAM UNGGULAN ===== --}}
    <div class="container">
        <section>
            <div class="sec-head">
                <div>
                    <h2>Program Kerja</h2>
                    <p>Kegiatan yang kami rencanakan untuk sekolah.</p>
                </div>
                <a href="{{ route('program') }}">Lihat semua program</a>
            </div>

            @if($programs->count())
                <div class="grid">
                    @foreach($programs as $pr)
                        @include('landing.partials.program-card', ['pr' => $pr])
                    @endforeach
                </div>
            @else
                <p class="kosong">Belum ada program kerja.</p>
            @endif
        </section>
    </div>

    {{-- ===== ACARA TERBARU ===== --}}
    <div class="container">
        <section class="band" style="padding:50px 4%;">
            <div class="sec-head">
                <div>
                    <h2>Acara Terbaru</h2>
                    <p>Klik kartu untuk membaca cerita lengkap acaranya.</p>
                </div>
                <a href="{{ route('acara.index') }}">Lihat semua acara</a>
            </div>

            @if($acaras->count())
                <div class="grid">
                    @foreach($acaras as $a)
                        @include('landing.partials.acara-card', ['a' => $a])
                    @endforeach
                </div>
            @else
                <p class="kosong">Belum ada acara.</p>
            @endif
        </section>
    </div>

    {{-- ===== GALERI ===== --}}
    @if(isset($galeri) && $galeri->count())
    <div class="container">
        <section>
            <div class="sec-head">
                <div>
                    <h2>Galeri Kegiatan</h2>
                    <p>Cuplikan momen dari kegiatan OSIS.</p>
                </div>
            </div>
            <div class="galeri">
                @foreach($galeri as $g)
                    <figure>
                        <img src="{{ asset('storage/'.$g->foto) }}" alt="{{ $g->keterangan ?: 'Foto kegiatan OSIS' }}" loading="lazy">
                        @if($g->keterangan)<figcaption>{{ $g->keterangan }}</figcaption>@endif
                    </figure>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    {{-- ===== AJAKAN PEMILIHAN ===== --}}
    <div class="container">
        <section>
            <div class="cta-band">
                <div>
                    <h2>Pemilihan Ketua OSIS</h2>
                    <p>Suaramu menentukan siapa yang memimpin OSIS berikutnya. Masuk dengan akun siswa untuk memilih.</p>
                </div>
                <button class="btn btn-light" onclick="toggleModal(true)">Masuk untuk memilih</button>
            </div>
        </section>
    </div>

@endsection
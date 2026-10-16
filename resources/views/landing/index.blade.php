@extends('landing.layout')
@section('title', ($p['hero_judul'] ?? 'OSIS SMK Pertiwi Kuningan'))

@push('styles')
<style>
    /* ===== HERO ===== */
    .hero { position:relative; min-height:100vh; min-height:100svh; display:flex; align-items:center; color:#fff;
            border-radius:0 0 36px 36px; overflow:hidden;
            background:linear-gradient(135deg,#075985,#0284c7); background-size:cover; background-position:var(--hero-pos, center 25%); }
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

    /* ===== SLIDER KARTU (Program Kerja & Acara) ===== */
    .slider { position:relative; }
    .track { position:relative; display:flex; gap:16px; overflow-x:auto; scroll-snap-type:x mandatory;
             scroll-behavior:smooth; -webkit-overflow-scrolling:touch; scrollbar-width:none;
             padding:6px 4px 18px; margin:0 -4px; scroll-padding-left:4px; }
    .track::-webkit-scrollbar { display:none; }
    .slide { flex:0 0 calc((100% - 32px) / 3); min-width:0; scroll-snap-align:start; display:flex; }
    .slide > .art { width:100%; }
    .slide .art-img { height:170px; }
    .slide .art-img img { object-position:center 30%; }
    .slide .art-body h3 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .slide .art-body p { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

    .slider-nav { display:flex; align-items:center; justify-content:center; gap:14px; margin-top:2px; }
    .slider-nav[hidden] { display:none; }
    .sl-dots { display:flex; align-items:center; gap:8px; }
    .sl-dot { width:8px; height:8px; border-radius:999px; border:none; padding:0; cursor:pointer;
              background:#bae6fd; transition:width .25s, background .25s; }
    .sl-dot.on { width:24px; background:var(--brand); }
    .sl-arrow { width:34px; height:34px; border-radius:50%; border:none; cursor:pointer; font-size:20px; line-height:1;
                background:#fff; color:var(--brand); box-shadow:0 3px 10px rgba(3,105,161,.15); transition:.2s; }
    .sl-arrow:hover:not(:disabled) { background:var(--soft); transform:translateY(-1px); }
    .sl-arrow:disabled { opacity:.4; cursor:default; }

    @media (max-width:900px){
        .slide { flex-basis:calc((100% - 16px) / 2); }
    }
    @media (max-width:560px){
        .slide { flex-basis:86%; }
        .slide .art-img { height:150px; }
    }

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
        /* HP: foto tampil lebih lega di atas, teks turun ke bawah di atas lapisan gelap */
        .hero { border-radius:0 0 26px 26px; min-height:80vh; min-height:80svh; align-items:flex-end; }
        .hero::before { background:linear-gradient(180deg, rgba(3,40,84,.30) 0%, rgba(3,40,84,.55) 40%, rgba(3,40,84,.92) 100%); }
        .hero-in { padding:110px 6% 44px; }
        .hero-btns .btn { flex:1 1 auto; text-align:center; }
        .cta-band { padding:32px 24px; }
        .cta-band .btn { width:100%; text-align:center; }
    }
    @media (prefers-reduced-motion:reduce){
        .track { scroll-behavior:auto; }
    }
</style>
@endpush

@section('content')

    @php
        $j1 = $p['hero_judul']  ?? '';
        $j2 = $p['hero_judul2'] ?? '';
        if ($j1 === '' && $j2 === '') { $j1 = 'OSIS SMK Pertiwi'; $j2 = 'Kuningan'; }

        // Titik fokus foto hero (opsional), format "50% 30%". Kalau kosong dipakai bawaan "center 25%".
        $pos = (isset($p['hero_posisi']) && preg_match('/^\d{1,3}% \d{1,3}%$/', $p['hero_posisi'])) ? $p['hero_posisi'] : null;
    @endphp

    {{-- ===== HERO ===== --}}
    <header class="hero"
        @if(!empty($p['hero_foto'])) style="background-image:url('{{ asset('storage/'.$p['hero_foto']) }}'){{ $pos ? ';--hero-pos:'.$pos : '' }}" @endif>
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
                <div class="slider" data-slider>
                    <div class="track">
                        @foreach($programs as $pr)
                            <div class="slide">
                                <a href="{{ route('program') }}" class="art">
                                    <div class="art-img">
                                        @if($pr->foto)
                                            <img src="{{ asset('storage/'.$pr->foto) }}" alt="{{ $pr->judul }}" loading="lazy">
                                        @else
                                            {{ $pr->ikon ?: '📌' }}
                                        @endif
                                    </div>
                                    <div class="art-body">
                                        <h3>{{ $pr->judul }}</h3>
                                        <p>{{ $pr->deskripsi }}</p>
                                        <span class="art-btn">Selengkapnya</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="slider-nav" hidden>
                        <button type="button" class="sl-arrow" data-prev aria-label="Sebelumnya">&lsaquo;</button>
                        <div class="sl-dots"></div>
                        <button type="button" class="sl-arrow" data-next aria-label="Berikutnya">&rsaquo;</button>
                    </div>
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
                <div class="slider" data-slider>
                    <div class="track">
                        @foreach($acaras as $a)
                            <div class="slide">
                                @include('landing.partials.acara-card', ['a' => $a])
                            </div>
                        @endforeach
                    </div>

                    <div class="slider-nav" hidden>
                        <button type="button" class="sl-arrow" data-prev aria-label="Sebelumnya">&lsaquo;</button>
                        <div class="sl-dots"></div>
                        <button type="button" class="sl-arrow" data-next aria-label="Berikutnya">&rsaquo;</button>
                    </div>
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

@push('scripts')
<script>
    // ===== Slider kartu: geser/swipe + titik halaman + tombol panah =====
    (function () {
        document.querySelectorAll('[data-slider]').forEach(function (sl) {
            var track  = sl.querySelector('.track');
            var slides = [].slice.call(track.children);
            var nav    = sl.querySelector('.slider-nav');
            var dotBox = sl.querySelector('.sl-dots');
            var prev   = sl.querySelector('[data-prev]');
            var next   = sl.querySelector('[data-next]');
            var perView = 1, pages = 1, dots = [], raf = 0;

            function gap()  { return parseFloat(getComputedStyle(track).columnGap) || 16; }
            function padL() { return parseFloat(getComputedStyle(track).paddingLeft) || 0; }

            function go(p) {
                p = Math.max(0, Math.min(pages - 1, p));
                var s = slides[Math.min(p * perView, slides.length - 1)];
                track.scrollTo({ left: s.offsetLeft - padL() });
            }

            function build() {
                var w = slides[0].offsetWidth + gap();
                perView = Math.max(1, Math.floor((track.clientWidth + gap()) / w));
                pages   = Math.ceil(slides.length / perView);

                dotBox.innerHTML = '';
                dots = [];
                for (var i = 0; i < pages; i++) {
                    (function (i) {
                        var d = document.createElement('button');
                        d.type = 'button';
                        d.className = 'sl-dot';
                        d.setAttribute('aria-label', 'Halaman ' + (i + 1));
                        d.addEventListener('click', function () { go(i); });
                        dotBox.appendChild(d);
                        dots.push(d);
                    })(i);
                }
                nav.hidden = pages <= 1;
            }

            function update() {
                var max = track.scrollWidth - track.clientWidth;
                var step = perView * (slides[0].offsetWidth + gap());
                var p = (track.scrollLeft >= max - 2) ? pages - 1 : Math.round(track.scrollLeft / step);
                p = Math.max(0, Math.min(pages - 1, p));
                dots.forEach(function (d, i) { d.classList.toggle('on', i === p); });
                prev.disabled = (p === 0);
                next.disabled = (p === pages - 1);
                sl._page = p;
            }

            prev.addEventListener('click', function () { go((sl._page || 0) - 1); });
            next.addEventListener('click', function () { go((sl._page || 0) + 1); });

            track.addEventListener('scroll', function () {
                if (raf) return;
                raf = requestAnimationFrame(function () { raf = 0; update(); });
            }, { passive: true });

            window.addEventListener('resize', function () { build(); update(); });

            build();
            update();
        });
    })();
</script>
@endpush
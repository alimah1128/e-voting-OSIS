@extends('admin.layouts.cms')
@section('title', 'Beranda')

@push('styles')
<style>
    .hero-prev { position:relative; min-height:380px; border-radius:26px; overflow:hidden; color:#fff; padding:70px 5% 50px;
                 background:linear-gradient(135deg,#032854,#0369a1); background-size:cover; background-position:center; }
    .hero-prev.has-img::before { content:''; position:absolute; inset:0;
                 background:linear-gradient(90deg, rgba(3,40,84,.85) 0%, rgba(7,89,133,.6) 55%, rgba(7,89,133,.35) 100%); }
    .hero-prev > * { position:relative; z-index:1; }
    .float-btns { position:absolute !important; top:16px; right:16px; display:flex; gap:8px; z-index:2; }
    .fbtn { background:#fff; color:var(--navy); border:none; border-radius:999px; padding:10px 18px; font-family:inherit; font-size:13px;
            font-weight:600; cursor:pointer; box-shadow:0 6px 16px rgba(0,0,0,.15); }
    .fbtn.danger { color:#b91c1c; }
    .hp-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.35);
                padding:6px 16px; border-radius:999px; font-size:12px; font-weight:600; margin-bottom:16px; }
    .hp-badge i { width:8px; height:8px; border-radius:50%; background:#38bdf8; display:block; }
    .hp-h1 { font-size:clamp(28px,5vw,52px); font-weight:700; line-height:1.15; max-width:720px; }
    .hp-h1 .l2 { display:block; color:#7dd3fc; }
    .hp-tag { margin-top:16px; max-width:520px; line-height:1.7; font-size:14.5px; opacity:.95; }
    .hp-btns { display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:26px; }
    .hp-btn { padding:11px 24px; border-radius:999px; font-weight:600; font-size:14px; border:1px solid rgba(255,255,255,.5); background:rgba(255,255,255,.1); }
    .hp-btn.p { background:linear-gradient(135deg,#00b4f0,#0090e0); border-color:transparent; }
    .pen { width:34px; height:34px; border-radius:50%; border:none; background:#fff; color:var(--navy); cursor:pointer; font-size:15px;
           display:inline-flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(0,0,0,.15); vertical-align:middle; margin-left:8px; }
    .hint { position:absolute !important; right:20px; bottom:14px; font-family:ui-monospace,Menlo,monospace; font-size:11px; opacity:.8; }
    .edit-card { margin-top:18px; }
    .edit-card h3 { margin-bottom:14px; }
    @media (max-width:760px){ .hero-prev { padding:70px 6% 44px; min-height:340px; } }
</style>
@endpush

@section('content')
    @php
        $j1 = $data['hero_judul']  ?? '';
        $j2 = $data['hero_judul2'] ?? '';
        if ($j1 === '' && $j2 === '') { $j1 = 'OSIS SMK Pertiwi'; $j2 = 'Kuningan'; }
        $foto = $data['hero_foto'] ?? '';
        $b1 = $data['hero_btn1_teks'] ?? '';
        $b2 = $data['hero_btn2_teks'] ?? '';
    @endphp

    <form method="POST" action="{{ route('admin.beranda.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="hapus_hero_foto" id="hapusFoto" value="0">
        <input type="file" id="heroFile" name="hero_foto" accept="image/*" hidden>

        {{-- Pratinjau hero (sama dengan tampilan website) --}}
        <section class="hero-prev {{ $foto ? 'has-img' : '' }}" id="heroPrev"
                 @if($foto) style="background-image:url('{{ asset('storage/'.$foto) }}')" @endif>
            <div class="float-btns">
                <label class="fbtn" for="heroFile">🖼 Ganti Foto Latar</label>
                <button type="button" class="fbtn danger" id="btnHapus" {!! $foto ? '' : 'hidden' !!}>Hapus Foto</button>
            </div>

            <div class="hp-badge" id="pvBadgeWrap" {!! !empty($data['hero_badge']) ? '' : 'hidden' !!}><i></i><span id="pvBadge">{{ $data['hero_badge'] ?? '' }}</span></div>

            <h1 class="hp-h1">
                <span id="pvJ1">{{ $j1 }}</span>
                <span class="l2" id="pvJ2">{{ $j2 }}</span>
            </h1>
            <button type="button" class="pen" data-focus="hero_judul" aria-label="Ubah judul">✎</button>

            <p class="hp-tag" id="pvTag" {!! !empty($data['hero_tagline']) ? '' : 'hidden' !!}>{{ $data['hero_tagline'] ?? '' }}</p>

            <div class="hp-btns">
                <span class="hp-btn p" id="pvB1">{{ $b1 !== '' ? $b1 : 'Tentang OSIS' }} →</span>
                <span class="hp-btn" id="pvB2">{{ $b2 !== '' ? $b2 : 'Lihat Acara' }}</span>
                <button type="button" class="pen" data-focus="hero_btn1_teks" aria-label="Ubah tombol">✎</button>
            </div>

            <span class="hint" id="pvHint" {!! $foto ? 'hidden' : '' !!}>[ foto latar belum diisi ]</span>
        </section>

        {{-- Isian teks --}}
        <div class="card edit-card">
            <h3>Teks Bagian Atas</h3>
            <div class="fg"><label class="lb" for="hero_badge">Badge slogan</label>
                <input type="text" id="hero_badge" name="hero_badge" value="{{ old('hero_badge', $data['hero_badge'] ?? '') }}" placeholder="Bergerak • Berkarya • Menginspirasi">
                <small>Tulisan kecil di atas judul. Kosongkan kalau tidak perlu.</small></div>

            <div class="row2">
                <div class="fg"><label class="lb" for="hero_judul">Judul baris 1 (putih)</label>
                    <input type="text" id="hero_judul" name="hero_judul" value="{{ old('hero_judul', $data['hero_judul'] ?? '') }}" placeholder="OSIS SMK Pertiwi"></div>
                <div class="fg"><label class="lb" for="hero_judul2">Judul baris 2 (biru muda)</label>
                    <input type="text" id="hero_judul2" name="hero_judul2" value="{{ old('hero_judul2', $data['hero_judul2'] ?? '') }}" placeholder="Kuningan"></div>
            </div>

            <div class="fg"><label class="lb" for="hero_tagline">Deskripsi</label>
                <textarea id="hero_tagline" name="hero_tagline" style="min-height:80px">{{ old('hero_tagline', $data['hero_tagline'] ?? '') }}</textarea>
                <small>Kalimat di bawah judul.</small></div>

            <div class="row2">
                <div class="fg"><label class="lb" for="hero_btn1_teks">Teks tombol 1</label>
                    <input type="text" id="hero_btn1_teks" name="hero_btn1_teks" value="{{ old('hero_btn1_teks', $data['hero_btn1_teks'] ?? '') }}" placeholder="Tentang OSIS"></div>
                <div class="fg"><label class="lb" for="hero_btn2_teks">Teks tombol 2</label>
                    <input type="text" id="hero_btn2_teks" name="hero_btn2_teks" value="{{ old('hero_btn2_teks', $data['hero_btn2_teks'] ?? '') }}" placeholder="Lihat Acara"></div>
            </div>

            <small style="color:#94a3b8;font-size:12px;display:block;margin-bottom:14px">Foto latar: gunakan foto landscape, lebar minimal 1600 px, maksimal 4 MB.</small>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                <a class="btn" href="{{ route('admin.beranda') }}">Batal</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    (function () {
        var $ = function (id) { return document.getElementById(id); };
        var prev = $('heroPrev'), file = $('heroFile');

        function teks(inputId, targetId, fallback, akhiran, sembunyiKosong, wrapId) {
            var inp = $(inputId), tgt = $(targetId);
            function ubah() {
                var v = inp.value.trim();
                tgt.textContent = (v !== '' ? v : fallback) + (akhiran || '');
                if (sembunyiKosong) { ($(wrapId || targetId)).hidden = (v === ''); }
            }
            inp.addEventListener('input', ubah);
        }

        // judul: kalau dua-duanya kosong, tampil bawaan seperti di website
        function judul() {
            var a = $('hero_judul').value.trim(), b = $('hero_judul2').value.trim();
            if (a === '' && b === '') { a = 'OSIS SMK Pertiwi'; b = 'Kuningan'; }
            $('pvJ1').textContent = a; $('pvJ2').textContent = b;
        }
        $('hero_judul').addEventListener('input', judul);
        $('hero_judul2').addEventListener('input', judul);

        teks('hero_badge', 'pvBadge', '', '', true, 'pvBadgeWrap');
        teks('hero_tagline', 'pvTag', '', '', true);
        teks('hero_btn1_teks', 'pvB1', 'Tentang OSIS', ' →');
        teks('hero_btn2_teks', 'pvB2', 'Lihat Acara');

        // pensil: lompat ke isian terkait
        document.querySelectorAll('.pen').forEach(function (b) {
            b.addEventListener('click', function () {
                var el = $(b.getAttribute('data-focus'));
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(function () { el.focus(); }, 350);
            });
        });

        // foto latar: pratinjau langsung
        file.addEventListener('change', function () {
            if (!file.files || !file.files[0]) return;
            var r = new FileReader();
            r.onload = function (e) {
                prev.style.backgroundImage = "url('" + e.target.result + "')";
                prev.classList.add('has-img');
                $('hapusFoto').value = '0';
                $('btnHapus').hidden = false;
                $('pvHint').hidden = true;
            };
            r.readAsDataURL(file.files[0]);
        });

        // hapus foto latar
        $('btnHapus').addEventListener('click', function () {
            $('hapusFoto').value = '1';
            file.value = '';
            prev.style.backgroundImage = '';
            prev.classList.remove('has-img');
            $('btnHapus').hidden = true;
            $('pvHint').hidden = false;
        });
    })();
</script>
@endpush
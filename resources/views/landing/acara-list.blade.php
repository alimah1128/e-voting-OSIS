@extends('landing.layout')
@section('title', 'Acara — OSIS SMK Pertiwi Kuningan')

@push('styles')
<style>
    .filter { display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-bottom:28px; }
    .filter button { font-family:inherit; font-size:14px; font-weight:600; padding:10px 22px; border-radius:999px; cursor:pointer;
                     border:1.5px solid #bae6fd; background:#fff; color:var(--sky-dark); transition:.2s; }
    .filter button:hover { background:#f0f9ff; }
    .filter button.on { background:linear-gradient(135deg,#00b4f0,#0090e0); color:#fff; border-color:transparent;
                        box-shadow:0 6px 16px rgba(0,144,224,.3); }
    .art[hidden] { display:none; }
</style>
@endpush

@section('content')

    @include('landing.partials.banner', [
        'judul' => 'Acara & Kegiatan',
        'sub'   => 'Kumpulan acara OSIS yang akan datang dan yang sudah berlalu.',
        'crumb' => 'Acara',
    ])

    <div class="container">
        <section>
            @if($acaras->count())
                <div class="filter" id="filter">
                    <button type="button" class="on" data-f="semua">Semua</button>
                    <button type="button" data-f="akan">Akan Datang</button>
                    <button type="button" data-f="selesai">Selesai</button>
                </div>

                <div class="grid" id="daftarAcara">
                    @foreach($acaras as $a)
                        @include('landing.partials.acara-card', ['a' => $a])
                    @endforeach
                </div>

                <p class="kosong" id="acaraKosong" hidden>Belum ada acara di kategori ini.</p>
            @else
                <p class="kosong">Belum ada acara.</p>
            @endif
        </section>
    </div>

@endsection

@push('scripts')
<script>
    (function(){
        var tombol = document.querySelectorAll('#filter button');
        var kartu  = document.querySelectorAll('#daftarAcara .art');
        var kosong = document.getElementById('acaraKosong');
        if(!tombol.length) return;

        tombol.forEach(function(b){
            b.addEventListener('click', function(){
                var f = b.getAttribute('data-f'), tampil = 0;
                tombol.forEach(function(x){ x.classList.toggle('on', x === b); });
                kartu.forEach(function(k){
                    var cocok = (f === 'semua' || k.getAttribute('data-status') === f);
                    k.hidden = !cocok;
                    if(cocok) tampil++;
                });
                kosong.hidden = tampil > 0;
            });
        });
    })();
</script>
@endpush
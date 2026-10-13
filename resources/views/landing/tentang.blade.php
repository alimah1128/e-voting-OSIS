@extends('landing.layout')
@section('title', 'Tentang — OSIS SMK Pertiwi Kuningan')

@section('content')

    @include('landing.partials.banner', [
        'judul' => 'Tentang OSIS',
        'sub'   => 'Mengenal OSIS SMK Pertiwi Kuningan: siapa kami, apa yang kami tuju, dan bagaimana kami bekerja.',
        'crumb' => 'Tentang',
    ])

    {{-- ===== PROFIL ===== --}}
    <div class="container">
        <section>
            @include('landing.partials.about', ['p' => $p, 'ringkas' => false])
        </section>
    </div>

    {{-- ===== VISI & MISI ===== --}}
    <div class="container">
        <section style="padding-top:0">
            <div class="grid">
                <div class="card hover">
                    <div class="ikon">🎯</div>
                    <h3>Visi</h3>
                    <p>{{ $p['visi'] ?? 'Belum diisi.' }}</p>
                </div>
                <div class="card hover">
                    <div class="ikon">🚀</div>
                    <h3>Misi</h3>
                    @if($misi->count())
                        <ul>@foreach($misi as $m)<li>{{ $m }}</li>@endforeach</ul>
                    @else
                        <p>Belum diisi.</p>
                    @endif
                </div>
            </div>
        </section>
    </div>

@endsection
@extends('landing.layout')
@section('title', 'Program Kerja — OSIS SMK Pertiwi Kuningan')

@section('content')

    @include('landing.partials.banner', [
        'judul' => 'Program Kerja',
        'sub'   => 'Kegiatan yang kami rencanakan untuk sekolah.',
        'crumb' => 'Program',
    ])

    <div class="container">
        <section>
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

@endsection
@extends('landing.layout')
@section('title', $acara->judul . ' — OSIS SMK Pertiwi Kuningan')

@push('styles')
<style>
    .detail { max-width:820px; margin:0 auto; }
    .detail-img { width:100%; max-height:420px; object-fit:cover; border-radius:24px; box-shadow:0 14px 34px rgba(2,132,199,.18); margin-bottom:24px; display:block; }
    .meta { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:22px; }
    .meta span { background:#fff; border-radius:999px; padding:8px 16px; font-size:13px; color:#475569;
                 box-shadow:0 4px 12px rgba(2,132,199,.08); }
    .meta span.status { background:#e0f2fe; color:var(--sky-dark); font-weight:600; }
    .meta span.status.selesai { background:#f1f5f9; color:#64748b; }
    .isi { background:#fff; border-radius:24px; padding:30px; box-shadow:0 8px 24px rgba(2,132,199,.08);
           color:#475569; line-height:1.9; font-size:15px; }
    .back { margin-top:24px; }
    @media (max-width:820px){ .isi { padding:22px; } }
</style>
@endpush

@section('content')

    @php $akan = $acara->tanggal->isFuture() || $acara->tanggal->isToday(); @endphp

    @include('landing.partials.banner', [
        'judul' => $acara->judul,
        'crumb' => 'Acara',
    ])

    <div class="container">
        <section>
            <article class="detail">
                @if($acara->foto)
                    <img class="detail-img" src="{{ asset('storage/'.$acara->foto) }}" alt="{{ $acara->judul }}">
                @endif

                <div class="meta">
                    <span class="status {{ $akan ? '' : 'selesai' }}">{{ $akan ? 'Akan Datang' : 'Selesai' }}</span>
                    <span>📅 {{ $acara->tanggal->translatedFormat('l, d F Y') }}</span>
                    @if($acara->lokasi)<span>📍 {{ $acara->lokasi }}</span>@endif
                </div>

                <div class="isi">{!! nl2br(e($acara->deskripsi)) !!}</div>

                <div class="back">
                    <a href="{{ route('acara.index') }}" class="btn btn-outline">Kembali ke daftar acara</a>
                </div>
            </article>
        </section>
    </div>

    @if($lainnya->count())
    <div class="container">
        <section class="band" style="padding:50px 4%;">
            <div class="sec-head">
                <div><h2>Acara Lainnya</h2></div>
                <a href="{{ route('acara.index') }}">Lihat semua acara</a>
            </div>
            <div class="grid">
                @foreach($lainnya as $a)
                    @include('landing.partials.acara-card', ['a' => $a])
                @endforeach
            </div>
        </section>
    </div>
    @endif

@endsection
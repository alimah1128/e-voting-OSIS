@extends('landing.layout')
@section('title', $acara->judul . ' — OSIS SMK Pertiwi Kuningan')

@push('styles')
<style>
    .detail { max-width:780px; margin:0 auto; }
    .detail-img { width:100%; max-height:300px; object-fit:cover; border-radius:18px; box-shadow:0 6px 20px rgba(2,132,199,.14); margin-bottom:14px; display:block; }
    .meta { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px; }
    .meta span { background:#fff; border-radius:999px; padding:6px 13px; font-size:12.5px; color:#475569;
                 box-shadow:0 3px 10px rgba(2,132,199,.07); }
    .meta span.status { background:#e0f2fe; color:var(--brand); font-weight:600; }
    .meta span.status.selesai { background:#f1f5f9; color:#64748b; }
    .isi { background:#fff; border-radius:18px; padding:20px; box-shadow:0 4px 16px rgba(2,132,199,.07);
           color:#475569; line-height:1.8; font-size:14.5px; }
    .back { margin-top:16px; }
    @media (max-width:820px){ .isi { padding:16px; } .detail-img { max-height:220px; } }
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
        <section class="band" style="padding:26px 4%;">
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
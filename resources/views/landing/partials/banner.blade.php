{{-- Pemakaian: @include('landing.partials.banner', ['judul' => 'Tentang OSIS', 'sub' => 'teks opsional', 'crumb' => 'Tentang']) --}}
<header class="page-banner">
    <div class="container">
        <div class="crumb">
            <a href="{{ route('landing') }}">Beranda</a> &rsaquo; {{ $crumb ?? $judul }}
        </div>
        <h1>{{ $judul }}</h1>
        @if(!empty($sub))
            <p>{{ $sub }}</p>
        @endif
    </div>
</header>
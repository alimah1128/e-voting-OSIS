{{-- Pemakaian: @include('admin.partials.page-head', ['judul' => '...', 'sub' => '...', 'kembali' => true]) --}}
<div class="page-head">
    <div>
        <h2 class="page-title">{{ $judul }}</h2>
        @if(!empty($sub)) <p class="page-sub">{{ $sub }}</p> @endif
    </div>
    @if(!empty($kembali))
        <a href="{{ route('admin.beranda') }}" class="btn btn-outline">&lsaquo; Kembali ke Beranda</a>
    @endif
</div>
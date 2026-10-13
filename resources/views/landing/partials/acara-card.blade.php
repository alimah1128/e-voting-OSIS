@php $akan = $a->tanggal->isFuture() || $a->tanggal->isToday(); @endphp
<a href="{{ route('acara.show', $a->id) }}" class="art" data-status="{{ $akan ? 'akan' : 'selesai' }}">
    <div class="art-img">
        @if($a->foto) <img src="{{ asset('storage/'.$a->foto) }}" alt="{{ $a->judul }}"> @else 📅 @endif
        <span class="art-tag {{ $akan ? '' : 'selesai' }}">{{ $akan ? 'Akan Datang' : 'Selesai' }}</span>
    </div>
    <div class="art-body">
        <div class="art-date">{{ $a->tanggal->translatedFormat('d F Y') }}@if($a->lokasi) &middot; {{ $a->lokasi }}@endif</div>
        <h3>{{ $a->judul }}</h3>
        <p>{{ \Illuminate\Support\Str::limit(strip_tags($a->deskripsi), 100) }}</p>
        <span class="art-btn">Baca Selengkapnya</span>
    </div>
</a>
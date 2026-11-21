<div class="card hover">
    @if($pr->foto)
        <img class="pic" src="{{ app(\App\Services\BlobStorage::class)->url($pr->foto) }}" alt="{{ $pr->judul }}">
    @else
        <div class="ikon">{{ $pr->ikon ?: '📌' }}</div>
    @endif
    <h3>{{ $pr->judul }}</h3>
    <p>{{ $pr->deskripsi }}</p>
</div>
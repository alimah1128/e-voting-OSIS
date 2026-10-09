@extends('admin.layouts.cms')
@section('title', $item ? 'Edit Acara' : 'Tambah Acara')

@section('content')
    <h2 class="page-title">{{ $item ? 'Edit' : 'Tambah' }} Acara</h2>
    <p class="page-sub">&nbsp;</p>

    <form class="card" method="POST" enctype="multipart/form-data"
          action="{{ $item ? route('admin.acara.update', $item->id) : route('admin.acara.store') }}">
        @csrf
        @if($item) @method('PUT') @endif

        <div class="fg"><label>Judul Acara</label>
            <input type="text" name="judul" value="{{ old('judul', $item->judul ?? '') }}" required></div>
        <div class="fg"><label>Deskripsi</label>
            <textarea name="deskripsi">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea></div>

        <div class="row2">
            <div class="fg"><label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $item?->tanggal?->format('Y-m-d')) }}" required></div>
            <div class="fg"><label>Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $item->lokasi ?? '') }}"></div>
        </div>

        <div class="fg"><label>Foto (opsional)</label>
            @if($item && $item->foto) <img class="thumb" style="width:90px;height:90px;margin-bottom:8px" src="{{ asset('storage/'.$item->foto) }}" alt=""> @endif
            <input type="file" name="foto" accept="image/*"><br><small>Maks 2 MB.</small></div>

        <div class="fg"><label style="text-transform:none;font-size:14px">
            <input type="checkbox" name="aktif" value="1" {{ old('aktif', $item->aktif ?? true) ? 'checked' : '' }}> Tampilkan di website</label></div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a class="btn" href="{{ route('admin.acara.index') }}">Batal</a>
        </div>
    </form>
@endsection
@extends('admin.layouts.cms')
@section('title', $item ? 'Edit Program Kerja' : 'Tambah Program Kerja')

@section('content')
    <h2 class="page-title">{{ $item ? 'Edit' : 'Tambah' }} Program Kerja</h2>
    <p class="page-sub">&nbsp;</p>

    <form class="card" method="POST" enctype="multipart/form-data"
          action="{{ $item ? route('admin.program-kerja.update', $item->id) : route('admin.program-kerja.store') }}">
        @csrf
        @if($item) @method('PUT') @endif

        <div class="fg"><label>Judul</label>
            <input type="text" name="judul" value="{{ old('judul', $item->judul ?? '') }}" required></div>
        <div class="fg"><label>Deskripsi</label>
            <textarea name="deskripsi">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea></div>

        <div class="row2">
            <div class="fg"><label>Ikon (emoji)</label>
                <input type="text" name="ikon" value="{{ old('ikon', $item->ikon ?? '') }}" placeholder="📚" maxlength="10">
                <small>Dipakai kalau tidak ada foto.</small></div>
            <div class="fg"><label>Urutan tampil</label>
                <input type="number" name="urutan" min="0" value="{{ old('urutan', $item->urutan ?? 0) }}"></div>
        </div>

        <div class="fg"><label>Foto (opsional)</label>
            @if($item && $item->foto) <img class="thumb" style="width:90px;height:90px;margin-bottom:8px" src="{{ asset('storage/'.$item->foto) }}" alt=""> @endif
            <input type="file" name="foto" accept="image/*"><br><small>Maks 2 MB. Kosongkan jika tidak ingin mengganti.</small></div>

        <div class="fg"><label style="text-transform:none;font-size:14px">
            <input type="checkbox" name="aktif" value="1" {{ old('aktif', $item->aktif ?? true) ? 'checked' : '' }}> Tampilkan di website</label></div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a class="btn" href="{{ route('admin.program-kerja.index') }}">Batal</a>
        </div>
    </form>
@endsection
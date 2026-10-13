@extends('admin.layouts.cms')
@section('title', 'Acara')

@section('content')
    @include('admin.partials.page-head', [
        'judul'   => 'Acara & Kegiatan',
        'sub'     => 'Kelola acara yang tampil sebagai kartu artikel di website.',
        'kembali' => true,
    ])

    <div class="split">
        {{-- ===== DAFTAR ===== --}}
        <div class="card">
            <div class="card-head">
                <h3>Daftar Acara</h3>
                <a class="btn btn-primary" href="{{ route('admin.acara.index') }}#formAcara">+ Tambah Acara</a>
            </div>

            <div class="tbl-wrap">
                <table>
                    <thead><tr><th>Foto</th><th>Acara</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @forelse($items as $i)
                        @php
                            $tgl  = \Carbon\Carbon::parse($i->tanggal);
                            $akan = $tgl->copy()->startOfDay()->gte(today());
                        @endphp
                        <tr class="{{ $item && $item->id === $i->id ? 'row-on' : '' }}">
                            <td>
                                @if($i->foto) <img class="thumb" src="{{ asset('storage/'.$i->foto) }}" alt="">
                                @else <div class="thumb ph">📅</div> @endif
                            </td>
                            <td class="judul">
                                <b>{{ $i->judul }}</b>
                                <small>{{ $tgl->translatedFormat('d F Y') }}@if($i->lokasi) &middot; {{ $i->lokasi }}@endif</small>
                            </td>
                            <td>
                                <span class="badge {{ $akan ? 'b-akan' : 'b-selesai' }}">{{ $akan ? 'Akan Datang' : 'Selesai' }}</span>
                                @if(!$i->aktif)<br><small style="color:#94a3b8;font-size:11px">Disembunyikan</small>@endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-soft btn-sm" href="{{ route('admin.acara.edit', $i->id) }}#formAcara">Edit</a>
                                    <form method="POST" action="{{ route('admin.acara.destroy', $i->id) }}" onsubmit="return confirm('Hapus acara ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="kosong">Belum ada acara. Tambahkan lewat form di samping.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== FORM TAMBAH / EDIT ===== --}}
        <form class="card" id="formAcara" method="POST" enctype="multipart/form-data"
              action="{{ $item ? route('admin.acara.update', $item->id) : route('admin.acara.store') }}">
            @csrf
            @if($item) @method('PUT') @endif

            <h3 style="margin-bottom:14px">{{ $item ? 'Edit' : 'Tambah' }} Acara</h3>

            <div class="fg">
                <label class="lb">Foto acara</label>
                <label for="fotoAcara" id="pbox" class="photo-box {{ $item && $item->foto ? 'has-img' : 'ph' }}"
                       @if($item && $item->foto) style="background-image:url('{{ asset('storage/'.$item->foto) }}')" @endif>[ unggah foto ]</label>
                <input type="file" id="fotoAcara" name="foto" accept="image/*" hidden onchange="previewFoto(this,'pbox')">
                <small>Maks 2 MB.</small>
            </div>

            <div class="fg"><label class="lb" for="judul">Judul acara</label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $item->judul ?? '') }}" placeholder="Judul acara" required></div>

            <div class="row2">
                <div class="fg"><label class="lb" for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal"
                           value="{{ old('tanggal', $item ? \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') : '') }}" required></div>
                <div class="fg"><label class="lb" for="lokasi">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $item->lokasi ?? '') }}" placeholder="Lokasi"></div>
            </div>

            <div class="fg"><label class="lb" for="deskripsi">Cerita lengkap</label>
                <textarea id="deskripsi" name="deskripsi" style="min-height:130px" placeholder="Isi artikel acara">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea></div>

            <div class="fg"><label class="lb" for="aktif">Tampil di website</label>
                @php $aktif = old('aktif', $item->aktif ?? true); @endphp
                <select id="aktif" name="aktif" class="{{ $aktif ? 'sel-on' : '' }}" onchange="this.classList.toggle('sel-on', this.value==='1')">
                    <option value="1" {{ $aktif ? 'selected' : '' }}>Ya, tampilkan</option>
                    <option value="0" {{ !$aktif ? 'selected' : '' }}>Tidak, sembunyikan</option>
                </select></div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <a class="btn" href="{{ route('admin.acara.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
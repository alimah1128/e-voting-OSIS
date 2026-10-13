@extends('admin.layouts.cms')
@section('title', 'Program Kerja')

@section('content')
    @include('admin.partials.page-head', [
        'judul'   => 'Program Kerja',
        'sub'     => 'Tambah, ubah, atau hapus program kerja yang tampil di website.',
        'kembali' => true,
    ])

    <div class="split">
        {{-- ===== DAFTAR ===== --}}
        <div class="card">
            <div class="card-head">
                <h3>Daftar Program Kerja</h3>
                <a class="btn btn-primary" href="{{ route('admin.program-kerja.index') }}#formProgram">+ Tambah Program</a>
            </div>

            <div class="tbl-wrap">
                <table>
                    <thead><tr><th>Foto</th><th>Judul</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @forelse($items as $i)
                        <tr class="{{ $item && $item->id === $i->id ? 'row-on' : '' }}">
                            <td>
                                @if($i->foto) <img class="thumb" src="{{ asset('storage/'.$i->foto) }}" alt="">
                                @else <div class="thumb ph">{{ $i->ikon ?: '📌' }}</div> @endif
                            </td>
                            <td class="judul">
                                <b>{{ $i->judul }}</b>
                                <small>{{ \Illuminate\Support\Str::limit($i->deskripsi, 50) }}</small>
                            </td>
                            <td><span class="badge {{ $i->aktif ? 'b-aktif' : 'b-non' }}">{{ $i->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-soft btn-sm" href="{{ route('admin.program-kerja.edit', $i->id) }}#formProgram">Edit</a>
                                    <form method="POST" action="{{ route('admin.program-kerja.destroy', $i->id) }}" onsubmit="return confirm('Hapus program ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="kosong">Belum ada program kerja. Tambahkan lewat form di samping.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== FORM TAMBAH / EDIT ===== --}}
        <form class="card" id="formProgram" method="POST" enctype="multipart/form-data"
              action="{{ $item ? route('admin.program-kerja.update', $item->id) : route('admin.program-kerja.store') }}">
            @csrf
            @if($item) @method('PUT') @endif

            <h3 style="margin-bottom:14px">{{ $item ? 'Edit' : 'Tambah' }} Program</h3>

            <div class="fg">
                <label class="lb">Foto program</label>
                <label for="fotoProgram" id="pbox" class="photo-box {{ $item && $item->foto ? 'has-img' : 'ph' }}"
                       @if($item && $item->foto) style="background-image:url('{{ asset('storage/'.$item->foto) }}')" @endif>[ unggah foto ]</label>
                <input type="file" id="fotoProgram" name="foto" accept="image/*" hidden onchange="previewFoto(this,'pbox')">
                <small>Maks 2 MB. Kalau tidak ada foto, yang tampil adalah ikon.</small>
            </div>

            <div class="fg"><label class="lb" for="judul">Judul</label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $item->judul ?? '') }}" placeholder="Judul program" required></div>

            <div class="fg"><label class="lb" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi program">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea></div>

            <div class="row2">
                <div class="fg"><label class="lb" for="urutan">Urutan</label>
                    <input type="number" id="urutan" name="urutan" min="0" value="{{ old('urutan', $item->urutan ?? 1) }}"></div>
                <div class="fg"><label class="lb" for="aktif">Status</label>
                    @php $aktif = old('aktif', $item->aktif ?? true); @endphp
                    <select id="aktif" name="aktif" class="{{ $aktif ? 'sel-on' : '' }}" onchange="this.classList.toggle('sel-on', this.value==='1')">
                        <option value="1" {{ $aktif ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$aktif ? 'selected' : '' }}>Nonaktif</option>
                    </select></div>
            </div>

            <div class="fg"><label class="lb" for="ikon">Ikon (opsional)</label>
                <input type="text" id="ikon" name="ikon" maxlength="10" value="{{ old('ikon', $item->ikon ?? '') }}" placeholder="📌">
                <small>Satu emoji, dipakai kalau program tidak punya foto.</small></div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <a class="btn" href="{{ route('admin.program-kerja.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
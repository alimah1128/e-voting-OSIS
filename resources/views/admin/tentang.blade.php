@extends('admin.layouts.cms')
@section('title', 'Tentang OSIS')

@section('content')
    @include('admin.partials.page-head', [
        'judul'   => 'Tentang OSIS',
        'sub'     => 'Foto, profil, visi, dan misi yang tampil di halaman Tentang website.',
        'kembali' => true,
    ])

    @php $foto = $data['tentang_foto'] ?? ''; @endphp

    <form class="card" method="POST" action="{{ route('admin.tentang.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="hapus_tentang_foto" id="hapusFoto" value="0">

        <div class="two">
            {{-- kiri: foto --}}
            <div>
                <label class="lb" style="display:block;font-size:11px;font-weight:600;color:#475569;margin-bottom:8px;text-transform:uppercase;letter-spacing:.04em">Foto Tentang OSIS</label>
                <label for="fotoTentang" id="pbox" class="photo-box big {{ $foto ? 'has-img' : 'ph' }}"
                       @if($foto) style="background-image:url('{{ asset('storage/'.$foto) }}')" @endif>[ foto Tentang OSIS ]</label>
                <input type="file" id="fotoTentang" name="tentang_foto" accept="image/*" hidden onchange="previewFoto(this,'pbox'); document.getElementById('hapusFoto').value='0'">

                <div class="actions">
                    <label for="fotoTentang" class="btn btn-soft">🖼 Ganti Foto</label>
                    <button type="button" class="btn btn-danger" id="btnHapus">Hapus Foto</button>
                </div>
                <small style="color:#94a3b8;font-size:12px;display:block;margin-top:10px">Maks 4 MB. Kalau kosong, yang tampil adalah logo OSIS.</small>
            </div>

            {{-- kanan: teks --}}
            <div>
                <div class="fg"><label class="lb" for="tentang_profil">Profil OSIS</label>
                    <textarea id="tentang_profil" name="tentang_profil" style="min-height:130px">{{ old('tentang_profil', $data['tentang_profil'] ?? '') }}</textarea></div>
                <div class="fg"><label class="lb" for="visi">Visi</label>
                    <textarea id="visi" name="visi" style="min-height:80px">{{ old('visi', $data['visi'] ?? '') }}</textarea></div>
                <div class="fg"><label class="lb" for="misi">Misi</label>
                    <textarea id="misi" name="misi" style="min-height:110px">{{ old('misi', $data['misi'] ?? '') }}</textarea>
                    <small>Satu baris = satu poin misi.</small></div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                    <a class="btn" href="{{ route('admin.tentang') }}">Batal</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.getElementById('btnHapus').addEventListener('click', function () {
        var box = document.getElementById('pbox');
        document.getElementById('hapusFoto').value = '1';
        document.getElementById('fotoTentang').value = '';
        box.style.backgroundImage = '';
        box.classList.remove('has-img');
        box.classList.add('ph');
    });
</script>
@endpush
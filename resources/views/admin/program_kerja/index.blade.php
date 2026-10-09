@extends('admin.layouts.cms')
@section('title', 'Program Kerja')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px">
        <div><h2 class="page-title">Program Kerja</h2><p class="page-sub">Daftar program kerja OSIS yang tampil di website.</p></div>
        <a class="btn btn-primary" href="{{ route('admin.program-kerja.create') }}">+ Tambah Program</a>
    </div>

    <div class="card tbl-wrap">
        <table>
            <thead><tr><th></th><th>Judul</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $i)
                <tr>
                    <td>
                        @if($i->foto) <img class="thumb" src="{{ asset('storage/'.$i->foto) }}" alt="">
                        @else <div class="thumb">{{ $i->ikon ?: '📌' }}</div> @endif
                    </td>
                    <td><b>{{ $i->judul }}</b><br><small style="color:#64748b">{{ \Illuminate\Support\Str::limit($i->deskripsi, 70) }}</small></td>
                    <td>{{ $i->urutan }}</td>
                    <td><span class="badge {{ $i->aktif ? 'b-on' : 'b-off' }}">{{ $i->aktif ? 'Tampil' : 'Disembunyikan' }}</span></td>
                    <td class="actions">
                        <a class="btn" href="{{ route('admin.program-kerja.edit', $i->id) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.program-kerja.destroy', $i->id) }}" onsubmit="return confirm('Hapus program ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:30px">Belum ada program kerja.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
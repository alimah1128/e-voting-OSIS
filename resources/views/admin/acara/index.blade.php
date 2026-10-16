@extends('admin.layouts.cms')
@section('title', 'Acara')

@section('content')
    @include('admin.partials.page-head', [
        'judul'   => 'Acara & Kegiatan',
        'sub'     => 'Kelola acara yang tampil sebagai kartu artikel di website.',
        'kembali' => true,
    ])

    {{-- Modal otomatis terbuka kalau: sedang mode edit, atau ada error validasi --}}
    @php $bukaModal = $item || $errors->any(); @endphp

    {{-- ===== DAFTAR ===== --}}
    <div class="card">
        <div class="card-head">
            <h3>Daftar Acara</h3>
            <button type="button" class="btn btn-primary" onclick="bukaFormModal()">+ Tambah Acara</button>
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
                                <a class="btn btn-soft btn-sm" href="{{ route('admin.acara.edit', $i->id) }}">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm"
                                        data-action="{{ route('admin.acara.destroy', $i->id) }}"
                                        data-judul="{{ $i->judul }}"
                                        data-foto="{{ $i->foto ? asset('storage/'.$i->foto) : '' }}"
                                        data-ikon="📅"
                                        onclick="bukaHapusModal(this)">Hapus</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="kosong">Belum ada acara. Klik tombol "+ Tambah Acara" di atas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== MODAL FORM TAMBAH / EDIT ===== --}}
    <div class="fm-overlay {{ $bukaModal ? 'open' : '' }}" id="fmOverlay" data-edit="{{ $item ? '1' : '0' }}" data-index="{{ route('admin.acara.index') }}">
        <form class="fm-box card" id="formAcara" method="POST" enctype="multipart/form-data"
              action="{{ $item ? route('admin.acara.update', $item->id) : route('admin.acara.store') }}">
            @csrf
            @if($item) @method('PUT') @endif

            <div class="fm-head">
                <h3>{{ $item ? 'Edit' : 'Tambah' }} Acara</h3>
                <button type="button" class="fm-x" onclick="tutupFormModal()" aria-label="Tutup">&times;</button>
            </div>

            @if($errors->any())
                <div class="fm-err">
                    <b>Ada kesalahan input:</b>
                    <ul>
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                <button class="btn" type="button" onclick="tutupFormModal()">Batal</button>
            </div>
        </form>
    </div>

    {{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
    <div class="hp-overlay" id="hpOverlay">
        <div class="hp-box" role="dialog" aria-modal="true" aria-labelledby="hpTitle">
            <div class="hp-thumb" id="hpThumb"></div>
            <h3 id="hpTitle">Hapus Acara</h3>
            <p class="hp-desc">Yakin ingin menghapus acara:<br><b id="hpNama"></b></p>
            <div class="hp-warn">&#9888; Tindakan ini tidak dapat dibatalkan.</div>
            <form method="POST" id="hpForm" action="">
                @csrf @method('DELETE')
                <div class="hp-actions">
                    <button type="submit" class="hp-yes">Ya, Hapus</button>
                    <button type="button" class="hp-no" onclick="tutupHapusModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* ===== Modal form (self-contained, prefix fm- supaya tidak bentrok) ===== */
        .fm-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .5);
            z-index: 1000;
            padding: 24px 16px;
            overflow-y: auto;
        }
        .fm-overlay.open { display: flex; }

        .fm-box.card {
            width: 100%;
            max-width: 520px;
            margin: auto;
            border-radius: 20px;
            padding: 24px 26px 22px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .18);
            animation: fmIn .18s ease;
        }
        .fm-box.card:hover { transform: none; }

        .fm-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 16px;
        }
        .fm-head h3 { margin: 0; }

        .fm-x {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            flex-shrink: 0;
            transition: background .2s ease, transform .15s ease;
        }
        .fm-x:hover { background: #e2e8f0; transform: scale(1.08); }

        .fm-err {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 14px;
        }
        .fm-err ul { margin: 4px 0 0 18px; }

        @keyframes fmIn {
            from { opacity: 0; transform: translateY(12px) scale(.98); }
            to   { opacity: 1; transform: none; }
        }

        @media (max-width: 600px) {
            .fm-overlay { padding: 12px; }
            .fm-box.card { padding: 20px 16px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .fm-box.card { animation: none; }
        }

        /* ===== Modal konfirmasi hapus ===== */
        .hp-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .5);
            z-index: 1001;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .hp-overlay.open { display: flex; }

        .hp-box {
            background: #fff;
            border-radius: 20px;
            padding: 28px 30px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .18);
            animation: fmIn .18s ease;
        }
        .hp-thumb {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            margin: 0 auto 12px;
            overflow: hidden;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .hp-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .hp-box h3 { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0 0 6px; }
        .hp-desc { font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 16px; }
        .hp-desc b { color: #2d6ba0; font-weight: 700; word-break: break-word; }
        .hp-warn {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 12px;
            text-align: left;
            margin-bottom: 20px;
        }
        .hp-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .hp-yes, .hp-no {
            font-family: inherit;
            font-weight: 700;
            font-size: 13px;
            padding: 10px 24px;
            border-radius: 999px;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }
        .hp-yes { background: #dc2626; color: #fff; border: none; }
        .hp-yes:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(220, 38, 38, .3); }
        .hp-no { background: #fff; color: #334155; border: 1px solid #cbd5e1; }
        .hp-no:hover { background: #f8fafc; transform: translateY(-2px); }
        .hp-yes:active, .hp-no:active { transform: scale(.97); }

        @media (max-width: 600px) {
            .hp-box { padding: 24px 18px; }
        }
    </style>

    <script>
        (function () {
            var overlay = document.getElementById('fmOverlay');
            var form    = document.getElementById('formAcara');
            var downOnOverlay = false;

            function resetFoto() {
                var box = document.getElementById('pbox');
                box.style.backgroundImage = '';
                box.classList.remove('has-img');
                box.classList.add('ph');
                document.getElementById('fotoAcara').value = '';
            }

            window.bukaFormModal = function () {
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
                setTimeout(function () {
                    var j = document.getElementById('judul');
                    if (j) j.focus();
                }, 50);
            };

            window.tutupFormModal = function () {
                // Mode edit: halaman ini adalah /edit, jadi balik ke daftar biasa
                if (overlay.dataset.edit === '1') {
                    window.location.href = overlay.dataset.index;
                    return;
                }
                overlay.classList.remove('open');
                document.body.style.overflow = '';

                // Bersihkan isi form (mode tambah)
                form.reset();
                resetFoto();
                var sel = document.getElementById('aktif');
                sel.classList.toggle('sel-on', sel.value === '1');
            };

            // Klik area gelap di luar kotak = tutup (hanya kalau klik-nya benar2 mulai & selesai di overlay)
            overlay.addEventListener('mousedown', function (e) { downOnOverlay = (e.target === overlay); });
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay && downOnOverlay) tutupFormModal();
            });

            // Tombol Esc
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && overlay.classList.contains('open')) tutupFormModal();
            });

            // Kalau modal terbuka otomatis (edit / error validasi), kunci scroll halaman
            if (overlay.classList.contains('open')) document.body.style.overflow = 'hidden';
        })();
    </script>
    <script>
        // ===== Modal konfirmasi hapus =====
        (function () {
            var ov   = document.getElementById('hpOverlay');
            var form = document.getElementById('hpForm');
            var downOnOverlay = false;

            window.bukaHapusModal = function (btn) {
                form.action = btn.dataset.action;
                document.getElementById('hpNama').textContent = btn.dataset.judul;

                var th = document.getElementById('hpThumb');
                th.innerHTML = '';
                if (btn.dataset.foto) {
                    var img = document.createElement('img');
                    img.src = btn.dataset.foto;
                    img.alt = '';
                    th.appendChild(img);
                } else {
                    th.textContent = btn.dataset.ikon;
                }

                ov.classList.add('open');
                document.body.style.overflow = 'hidden';
            };

            window.tutupHapusModal = function () {
                ov.classList.remove('open');
                document.body.style.overflow = '';
            };

            ov.addEventListener('mousedown', function (e) { downOnOverlay = (e.target === ov); });
            ov.addEventListener('click', function (e) {
                if (e.target === ov && downOnOverlay) tutupHapusModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && ov.classList.contains('open')) tutupHapusModal();
            });
        })();
    </script>
@endsection
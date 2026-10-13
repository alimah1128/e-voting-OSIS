@extends('admin.layouts.cms')
@section('title', 'Dashboard Pemilihan')

@push('styles')
<style>
    .stat-cards { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:18px; }
    .stat-card { background:#fff; border-radius:20px; padding:20px; box-shadow:0 6px 22px rgba(2,132,199,.07); transition:.25s; }
    .stat-card:hover { transform:translateY(-4px); box-shadow:0 14px 30px rgba(2,132,199,.14); }
    .stat-card .ic { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; margin-bottom:12px; }
    .ic-blue { background:#dbeafe; } .ic-green { background:#dcfce7; } .ic-yellow { background:#fef3c7; } .ic-purple { background:#ede9fe; }
    .stat-card .num { font-size:28px; font-weight:700; color:#1e293b; line-height:1.2; }
    .stat-card .lbl { font-size:12.5px; color:#64748b; margin-top:2px; }

    .panel { margin-bottom:16px; }
    .panel .ttl { font-size:13px; font-weight:600; color:var(--navy); margin-bottom:12px; }
    .aktif-row { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
    .aktif-row .nama { font-size:17px; font-weight:600; color:#1e293b; }
    .aktif-row .tgl { font-size:12.5px; color:#94a3b8; margin-top:2px; }
    .empty { font-size:13px; color:#94a3b8; font-style:italic; }
    .p-row { display:flex; align-items:center; justify-content:space-between; gap:10px; padding:12px 14px; background:#f8fafc;
             border-radius:12px; margin-bottom:8px; font-size:14px; color:#334155; transition:.2s; }
    .p-row:last-child { margin-bottom:0; }
    .p-row:hover { background:#eff6ff; }
    .p-row span:first-child { min-width:0; overflow-wrap:anywhere; }
    .b-active { background:#dcfce7; color:#166534; }
    .b-inactive { background:#fee2e2; color:#b91c1c; }
    .b-draft { background:#e2e8f0; color:#475569; }

    @media (max-width:900px){ .stat-cards { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:340px){ .stat-cards { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
    @include('admin.partials.page-head', [
        'judul' => 'Dashboard Pemilihan',
        'sub'   => 'Ringkasan e-voting pemilihan Ketua OSIS.',
    ])

    <div class="stat-cards">
        <div class="stat-card">
            <div class="ic ic-blue">👥</div>
            <div class="num">{{ $jumlahSiswa }}</div>
            <div class="lbl">Jumlah Siswa</div>
        </div>
        <div class="stat-card">
            <div class="ic ic-green">✅</div>
            <div class="num">{{ $sudahVoting }}</div>
            <div class="lbl">Sudah Voting</div>
        </div>
        <div class="stat-card">
            <div class="ic ic-yellow">⏳</div>
            <div class="num">{{ $belumVoting }}</div>
            <div class="lbl">Belum Voting</div>
        </div>
        <div class="stat-card">
            <div class="ic ic-purple">🏆</div>
            <div class="num">{{ $jumlahKandidat }}</div>
            <div class="lbl">Jumlah Kandidat</div>
        </div>
    </div>

    {{-- Status periode aktif --}}
    <div class="card panel">
        <div class="ttl">Status Periode Voting</div>
        @if ($periodeAktif)
            <div class="aktif-row">
                <div>
                    <div class="nama">{{ $periodeAktif->nama_periode }}</div>
                    <div class="tgl">{{ $periodeAktif->tanggal_mulai }} — {{ $periodeAktif->tanggal_selesai }}</div>
                </div>
                <span class="badge b-active">Active</span>
            </div>
        @else
            <div class="empty">Belum ada periode voting yang aktif saat ini.</div>
        @endif
    </div>

    {{-- Semua periode --}}
    <div class="card panel">
        <div class="ttl">Semua Periode</div>
        @forelse ($semuaPeriode as $periode)
            <div class="p-row">
                <span>{{ $periode->nama_periode }}</span>
                <span class="badge b-{{ $periode->status }}">{{ ucfirst($periode->status) }}</span>
            </div>
        @empty
            <div class="empty">Belum ada data periode pemilihan.</div>
        @endforelse
    </div>
@endsection
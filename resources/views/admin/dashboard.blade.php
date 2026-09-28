@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    .content h1 {
        font-size: 22px;
        color: #14406B;
        margin-bottom: 4px;
    }

    .welcome-text {
        font-size: 13px;
        color: #64748B;
        margin-bottom: 22px;
    }

    .stat-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        padding: 20px;
        transition: box-shadow 0.25s ease, transform 0.25s ease;
    }

    .stat-card:hover {
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
    }

    .stat-card:active {
        transform: translateY(-1px) scale(0.98);
    }

    .stat-card .icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #fff;
        margin-bottom: 14px;
    }

    .stat-card .number {
        font-size: 26px;
        font-weight: 700;
        color: #1E293B;
    }

    .stat-card .label {
        font-size: 12px;
        color: #64748B;
        margin-top: 2px;
    }

    .icon-blue { background: #4C7CE0; }
    .icon-green { background: #22C55E; }
    .icon-orange { background: #F59E0B; }
    .icon-purple { background: #A855F7; }

    .dash-panel {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        padding: 20px 22px;
        margin-bottom: 16px;
        transition: box-shadow 0.25s ease, transform 0.25s ease;
    }

    .dash-panel:hover {
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .dash-panel .panel-title {
        font-size: 13px;
        font-weight: 700;
        color: #1E5A96;
        margin-bottom: 10px;
    }

    .periode-active-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .periode-active-row .nama {
        font-size: 16px;
        font-weight: 700;
        color: #1E293B;
    }

    .periode-active-row .tanggal {
        font-size: 12px;
        color: #94A3B8;
        margin-top: 2px;
    }

    .empty-note {
        font-size: 12px;
        color: #94A3B8;
        font-style: italic;
    }

    .badge-dash {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .badge-dash.badge-active { background: #DCFCE7; color: #16A34A; }
    .badge-dash.badge-inactive { background: #FEE2E2; color: #DC2626; }
    .badge-dash.badge-draft { background: #F1F5F9; color: #64748B; }

    .periode-list-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 14px;
        background: #F8FAFC;
        border-radius: 10px;
        margin-bottom: 8px;
        font-size: 13px;
        color: #334155;
        transition: background 0.2s ease, box-shadow 0.2s ease;
    }

    .periode-list-row span:first-child {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .periode-list-row:hover {
        background: #EFF6FF;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .periode-list-row:last-child {
        margin-bottom: 0;
    }

    /* ===== RESPONSIF ===== */
    @media (max-width: 900px) {
        .stat-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .content h1 {
            font-size: 19px;
        }

        .welcome-text {
            margin-bottom: 16px;
        }

        .stat-cards {
            gap: 12px;
        }

        .stat-card {
            padding: 16px;
        }

        .stat-card .icon {
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 22px;
        }

        .dash-panel {
            padding: 16px;
        }

        .periode-active-row .nama {
            font-size: 15px;
        }
    }

    @media (max-width: 340px) {
        .stat-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

<h1>Dashboard Admin</h1>
<div class="welcome-text">Selamat datang di panel administrasi E-Voting OSIS</div>

<!-- STAT CARDS -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="icon icon-blue"><i class="fa-solid fa-users"></i></div>
        <div class="number">{{ $jumlahSiswa }}</div>
        <div class="label">Jumlah Siswa</div>
    </div>
    <div class="stat-card">
        <div class="icon icon-green"><i class="fa-solid fa-check"></i></div>
        <div class="number">{{ $sudahVoting }}</div>
        <div class="label">Sudah Voting</div>
    </div>
    <div class="stat-card">
        <div class="icon icon-orange"><i class="fa-solid fa-hourglass-half"></i></div>
        <div class="number">{{ $belumVoting }}</div>
        <div class="label">Belum Voting</div>
    </div>
    <div class="stat-card">
        <div class="icon icon-purple"><i class="fa-solid fa-trophy"></i></div>
        <div class="number">{{ $jumlahKandidat }}</div>
        <div class="label">Jumlah Kandidat</div>
    </div>
</div>

<!-- STATUS PERIODE AKTIF -->
<div class="dash-panel">
    <div class="panel-title">Status Periode Voting</div>

    @if ($periodeAktif)
        <div class="periode-active-row">
            <div>
                <div class="nama">{{ $periodeAktif->nama_periode }}</div>
                <div class="tanggal">{{ $periodeAktif->tanggal_mulai }} — {{ $periodeAktif->tanggal_selesai }}</div>
            </div>
            <span class="badge-dash badge-active">Active</span>
        </div>
    @else
        <div class="empty-note">Belum ada periode voting yang aktif saat ini.</div>
    @endif
</div>

<!-- SEMUA PERIODE -->
<div class="dash-panel">
    <div class="panel-title">Semua Periode</div>

    @forelse ($semuaPeriode as $periode)
        <div class="periode-list-row">
            <span>{{ $periode->nama_periode }}</span>
            <span class="badge-dash badge-{{ $periode->status }}">
                {{ ucfirst($periode->status) }}
            </span>
        </div>
    @empty
        <div class="empty-note">Belum ada data periode pemilihan.</div>
    @endforelse
</div>
@endsection
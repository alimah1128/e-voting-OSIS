@extends('layouts.admin')

@section('title', 'Belum Memilih')

@section('content')
<style>
    .hasil-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 20px;
    }

    .hasil-select-wrap {
        position: relative;
        min-width: 300px;
    }

    .hasil-select-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #2D6BA0;
        font-size: 13px;
        pointer-events: none;
    }

    .hasil-select-wrap select {
        width: 100%;
        appearance: none;
        padding: 12px 36px 12px 38px;
        border-radius: 14px;
        border: 1px solid #E2E8F0;
        background: #fff;
        font-size: 13px;
        font-weight: 600;
        color: #14406B;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2394A3B8' d='M6 8 0 0h12z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .hasil-select-wrap select:hover,
    .hasil-select-wrap select:focus {
        border-color: #38BDF8;
        box-shadow: 0 4px 14px rgba(56,189,248,0.15);
        outline: none;
    }

    .periode-info-card {
        background: linear-gradient(120deg, #14406B, #2D6BA0);
        border-radius: 18px;
        padding: 20px 26px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        box-shadow: 0 8px 20px rgba(20,64,107,0.18);
    }

    .periode-info-card .nama {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .periode-info-card .tanggal {
        font-size: 12px;
        opacity: 0.85;
    }

    .periode-info-card .tanggal i {
        margin-right: 6px;
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(255,255,255,0.18);
        backdrop-filter: blur(2px);
    }

    .badge-status-pill.is-active { background: #22C55E; }
    .badge-status-pill.is-draft { background: #94A3B8; }
    .badge-status-pill.is-inactive { background: #EF4444; }

    .panel {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        border: 1px solid #EEF2F7;
        box-shadow: 0 3px 12px rgba(0,0,0,0.03);
    }

    .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #14406B;
        margin-bottom: 4px;
    }

    .panel-subtitle {
        font-size: 12px;
        color: #94A3B8;
        margin-bottom: 16px;
    }

    /* Pembungkus tabel: bisa digeser ke samping kalau layar sempit */
    .table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-hasil {
        width: 100%;
        border-collapse: collapse;
    }

    .table-hasil thead th {
        text-align: left;
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94A3B8;
        padding: 10px 12px;
        border-bottom: 1px solid #F1F5F9;
        white-space: nowrap;
    }

    .table-hasil tbody tr {
        transition: background 0.15s ease;
    }

    .table-hasil tbody tr:hover {
        background: #F0F9FF;
    }

    .table-hasil tbody tr.is-top {
        background: #FEF2F2;
    }

    .table-hasil tbody tr.is-top:hover {
        background: #FEE2E2;
    }

    .table-hasil tbody tr.is-selected {
        background: #E0F2FE;
    }

    .table-hasil td {
        padding: 14px 12px;
        font-size: 13.5px;
        color: #334155;
        border-bottom: 1px solid #F8FAFC;
    }

    .kelas-nama {
        font-weight: 700;
        color: #14406B;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
    }

    .kelas-nama:hover {
        text-decoration: underline;
    }

    .tanda-perhatian {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #DC2626;
        color: #fff;
        font-size: 11px;
        cursor: help;
    }

    .jumlah-pill {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        background: #FEF2F2;
        color: #DC2626;
        white-space: nowrap;
    }

    .progress-bar-track {
        width: 100%;
        max-width: 160px;
        height: 8px;
        border-radius: 20px;
        background: #F1F5F9;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #F87171, #DC2626);
    }

    .progress-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .progress-persen {
        font-size: 12px;
        font-weight: 700;
        color: #DC2626;
        min-width: 34px;
    }

    /* ===== RESPONSIF ===== */
    @media (max-width: 600px) {
        .hasil-select-wrap {
            min-width: 0;
            width: 100%;
        }

        .periode-info-card {
            padding: 16px 18px;
        }

        .panel {
            padding: 16px;
        }

        /* Lebar minimum supaya kolom tidak terjepit, sisanya bisa digeser */
        .table-hasil {
            min-width: 440px;
        }

        .table-hasil td {
            padding: 12px 10px;
            font-size: 13px;
        }

        .table-hasil td,
        .table-hasil th {
            white-space: nowrap;
        }
    }
</style>

<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>

    <div class="header-action">
        <div>
            <h2>Belum Memilih</h2>
            <p>Rekap kelas dengan siswa yang belum melakukan voting. Klik nama kelas untuk lihat daftar siswanya.</p>
        </div>
    </div>

    @if ($periodes->isEmpty())
        <div class="form-error-box" style="max-width:none;">
            Belum ada data periode. Buat periode dulu di menu Kelola Periode.
        </div>
    @else
        <div class="hasil-header-row">
            <form method="GET" action="{{ route('admin.belumMemilih') }}" class="hasil-select-wrap">
                <i class="fa-solid fa-calendar-days"></i>
                <select name="periode_id" onchange="this.form.submit()">
                    @foreach ($periodes as $p)
                        <option value="{{ $p->id }}" {{ $periodeTerpilih->id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_periode }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="periode-info-card">
            <div>
                <div class="nama">{{ $periodeTerpilih->nama_periode }}</div>
                <div class="tanggal"><i class="fa-regular fa-clock"></i>{{ $periodeTerpilih->tanggal_mulai }} — {{ $periodeTerpilih->tanggal_selesai }}</div>
            </div>
            <span class="badge-status-pill is-{{ $periodeTerpilih->status }}">
                <i class="fa-solid fa-circle" style="font-size:7px;"></i> {{ ucfirst($periodeTerpilih->status) }}
            </span>
        </div>

        <div class="panel">
            <div class="panel-title">Rekap Belum Memilih per Kelas</div>
            <div class="panel-subtitle">Diurutkan dari kelas dengan persentase siswa belum voting tertinggi. Klik nama kelas untuk detail siswanya.</div>

            <div class="table-scroll">
                <table class="table-hasil">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Belum Memilih</th>
                            <th>Persentase Belum Memilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataKelas as $row)
                            <tr class="{{ $row->perlu_perhatian ? 'is-top' : '' }} {{ $kelasTerpilih === $row->kelas ? 'is-selected' : '' }}">
                                <td>
                                    <a href="{{ route('admin.belumMemilih', ['periode_id' => $periodeTerpilih->id, 'kelas' => $row->kelas]) }}" class="kelas-nama">
                                        {{ $row->kelas }}
                                        @if ($row->perlu_perhatian)
                                            <span class="tanda-perhatian" title="Sebagian besar siswa belum memilih">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </span>
                                        @endif
                                    </a>
                                </td>
                                <td><span class="jumlah-pill">{{ $row->jumlah }} dari {{ $row->total }} siswa</span></td>
                                <td>
                                    <div class="progress-wrap">
                                        <div class="progress-bar-track">
                                            <div class="progress-bar-fill" style="width: {{ $row->persen }}%"></div>
                                        </div>
                                        <span class="progress-persen">{{ $row->persen }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; color:#16A34A; padding: 30px 12px;">🎉 Semua siswa sudah memilih di semua kelas!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($kelasTerpilih)
            <div class="panel" style="margin-top:20px;">
                <div class="panel-title">Siswa Belum Memilih — Kelas {{ $kelasTerpilih }}</div>
                <div class="panel-subtitle">{{ $siswaBelum->count() }} siswa belum melakukan voting di periode {{ $periodeTerpilih->nama_periode }}</div>

                <div class="table-scroll">
                    <table class="table-hasil">
                        <thead>
                            <tr>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswaBelum as $s)
                                <tr>
                                    <td>{{ $s->nis }}</td>
                                    <td>{{ $s->nama }}</td>
                                    <td>{{ $s->username }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" style="text-align:center; color:#16A34A; padding: 20px 12px;">🎉 Semua siswa di kelas ini sudah memilih!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
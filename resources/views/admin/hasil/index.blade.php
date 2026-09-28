@extends('layouts.admin')

@section('title', 'Hasil Voting')

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

    .badge-status-pill.is-active {
        background: #22C55E;
    }

    .badge-status-pill.is-draft {
        background: #94A3B8;
    }

    .badge-status-pill.is-inactive {
        background: #EF4444;
    }

    .hasil-stat-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin: 0 0 20px;
    }

    .hasil-stat-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px 20px;
        border: 1px solid #EEF2F7;
        box-shadow: 0 3px 12px rgba(0,0,0,0.03);
        cursor: default;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }

    .hasil-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 30px rgba(45,107,160,0.14);
        border-color: #BAE6FD;
    }

    .hasil-stat-card .icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        margin-bottom: 14px;
        color: #fff;
    }

    .hasil-stat-card .icon-badge.blue { background: linear-gradient(135deg, #38BDF8, #2D6BA0); }
    .hasil-stat-card .icon-badge.green { background: linear-gradient(135deg, #4ADE80, #16A34A); }
    .hasil-stat-card .icon-badge.purple { background: linear-gradient(135deg, #C084FC, #7C3AED); }

    .hasil-stat-card .number {
        font-size: 30px;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.1;
    }

    .hasil-stat-card .label {
        font-size: 12px;
        color: #64748B;
        font-weight: 600;
        margin-top: 4px;
    }

    .partisipasi-track {
        width: 100%;
        height: 9px;
        background: #F1F5F9;
        border-radius: 20px;
        overflow: hidden;
        margin-top: 12px;
    }

    .partisipasi-fill {
        height: 100%;
        background: linear-gradient(90deg, #4ADE80, #16A34A);
        border-radius: 20px;
        transition: width 0.4s ease;
    }

    .hasil-grid {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 18px;
    }

    /* min-width 0 supaya isi panel (tabel/grafik) tidak mendorong lebar halaman */
    .hasil-grid > .panel {
        min-width: 0;
    }

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
        margin-bottom: 14px;
    }

    .panel-head-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }

    .panel-head-row .panel-title {
        margin-bottom: 0;
    }

    /* Pembungkus tabel: bisa digeser ke samping kalau layar sempit */
    .table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .chart-wrap {
        position: relative;
        width: 100%;
        height: 260px;
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
        padding: 8px 10px;
        border-bottom: 1px solid #F1F5F9;
    }

    .table-hasil tbody tr {
        transition: background 0.15s ease, transform 0.15s ease;
        border-radius: 12px;
    }

    .table-hasil tbody tr:hover {
        background: #F0F9FF;
    }

    .table-hasil tbody tr:hover .rank-badge {
        transform: scale(1.12);
    }

    .table-hasil td {
        padding: 12px 10px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #F8FAFC;
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #2D6BA0;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        transition: transform 0.15s ease;
    }

    .persen-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #EFF6FF;
        color: #2D6BA0;
    }

    .chart-toggle-group {
        display: flex;
        gap: 4px;
        background: #F1F5F9;
        border-radius: 10px;
        padding: 3px;
    }

    .chart-toggle-btn {
        padding: 6px 14px;
        border-radius: 8px;
        border: none;
        background: transparent;
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }

    .chart-toggle-btn.selected {
        background: #2D6BA0;
        color: #fff;
    }

    .aksi-lanjutan-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .aksi-lanjutan-row .btn-primary,
    .aksi-lanjutan-row .btn-cancel-outline,
    .aksi-lanjutan-row .btn-danger-solid {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .aksi-lanjutan-row .btn-primary:hover,
    .aksi-lanjutan-row .btn-cancel-outline:hover,
    .aksi-lanjutan-row .btn-danger-solid:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.12);
    }

    /* ===== RESPONSIF ===== */
    @media (max-width: 900px) {
        .hasil-stat-cards { grid-template-columns: 1fr; }
        .hasil-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 600px) {
        .hasil-select-wrap {
            min-width: 0;
            width: 100%;
        }

        .periode-info-card {
            padding: 16px 18px;
        }

        .hasil-stat-card {
            padding: 18px 16px;
        }

        .hasil-stat-card .number {
            font-size: 26px;
        }

        .panel {
            padding: 16px;
        }

        .table-hasil {
            min-width: 340px;
        }

        .chart-wrap {
            height: 240px;
        }

        /* Tombol aksi jadi selebar layar supaya gampang ditekan */
        .aksi-lanjutan-row {
            flex-direction: column;
        }

        .aksi-lanjutan-row form {
            display: block !important;
            width: 100%;
        }

        .aksi-lanjutan-row .btn-primary,
        .aksi-lanjutan-row .btn-cancel-outline,
        .aksi-lanjutan-row .btn-danger-solid {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
        }
    }
</style>

<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>

    <div class="header-action">
        <div>
            <h2>Hasil Voting</h2>
            <p>Rekapitulasi hasil pemilihan</p>
        </div>
    </div>

    @if ($periodes->isEmpty())
        <div class="form-error-box" style="max-width:none;">
            Belum ada data periode. Buat periode dulu di menu Kelola Periode.
        </div>
    @else
        <div class="hasil-header-row">
            <form method="GET" action="{{ route('admin.hasil.index') }}" class="hasil-select-wrap">
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

        <!-- INFO PERIODE -->
        <div class="periode-info-card">
            <div>
                <div class="nama">{{ $periodeTerpilih->nama_periode }}</div>
                <div class="tanggal"><i class="fa-regular fa-clock"></i>{{ $periodeTerpilih->tanggal_mulai }} — {{ $periodeTerpilih->tanggal_selesai }}</div>
            </div>
            <span class="badge-status-pill is-{{ $periodeTerpilih->status }}">
                <i class="fa-solid fa-circle" style="font-size:7px;"></i> {{ ucfirst($periodeTerpilih->status) }}
            </span>
        </div>

        <!-- STAT CARDS -->
        <div class="hasil-stat-cards">
            <div class="hasil-stat-card">
                <div class="icon-badge blue"><i class="fa-solid fa-ballot"></i></div>
                <div class="number">{{ $totalSuara }}</div>
                <div class="label">Total Suara Masuk</div>
            </div>

            <div class="hasil-stat-card">
                <div class="icon-badge green"><i class="fa-solid fa-chart-pie"></i></div>
                <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:6px;">
                    <span style="color:#16A34A; font-weight:700;">Sudah: {{ $sudahVoting }}</span>
                    <span style="color:#F59E0B; font-weight:700;">Belum: {{ $belumVoting }}</span>
                </div>
                <div class="partisipasi-track">
                    <div class="partisipasi-fill" style="width: {{ $partisipasi }}%;"></div>
                </div>
                <div style="font-size:11px; color:#94A3B8; margin-top:8px;">dari {{ $totalSiswa }} total pemilih</div>
            </div>

            <div class="hasil-stat-card">
                <div class="icon-badge purple"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <div class="number" style="color:#2D6BA0;">{{ $partisipasi }}%</div>
                <div class="label">Partisipasi Voting</div>
            </div>
        </div>

        <!-- TABEL + CHART -->
        <div class="hasil-grid">
            <div class="panel">
                <div class="panel-title">Perolehan Suara per Kandidat</div>
                <div class="table-scroll">
                    <table class="table-hasil">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kandidat</th>
                                <th>Suara</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hasilPerKandidat as $item)
                                <tr>
                                    <td><span class="rank-badge">{{ $item->kandidat->no_urut }}</span></td>
                                    <td>
                                        <strong>{{ $item->kandidat->nama_ketua }}</strong><br>
                                        <span style="font-size:11px; color:#94A3B8;">& {{ $item->kandidat->nama_wakil }}</span>
                                    </td>
                                    <td>{{ $item->suara }}</td>
                                    <td><span class="persen-pill">{{ $item->persen }}%</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align:center; color:#94A3B8;">Belum ada kandidat di periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head-row">
                    <div class="panel-title">Grafik Hasil Voting</div>
                    <div class="chart-toggle-group">
                        <button type="button" class="chart-toggle-btn selected" id="btnChartBar" onclick="gantiChart('bar')">Bar</button>
                        <button type="button" class="chart-toggle-btn" id="btnChartPie" onclick="gantiChart('pie')">Pie</button>
                    </div>
                </div>
                <div class="chart-wrap">
                    <canvas id="chartHasilVoting"></canvas>
                </div>
            </div>
        </div>

        <!-- AKSI LANJUTAN -->
        <div class="panel" style="margin-top:18px;">
            <div class="panel-title">Aksi Lanjutan</div>
            <div class="aksi-lanjutan-row">
                @if ($periodeTerpilih->status === 'active')
                    <form action="{{ route('admin.periode.updateStatus', $periodeTerpilih->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="inactive">
                        <button type="submit" class="btn-cancel-outline">
                            <i class="fa-solid fa-lock"></i> Ubah Status Periode → Inactive
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.hasil.exportExcel', $periodeTerpilih->id) }}" class="btn-primary">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>

                <a href="{{ route('admin.hasil.exportPdf', $periodeTerpilih->id) }}" class="btn-primary">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>

                <button type="button" class="btn-danger-solid" onclick="bukaModalReset()">
                    <i class="fa-solid fa-triangle-exclamation"></i> Reset Data Vote
                </button>
            </div>
        </div>

        <!-- MODAL RESET -->
        <div class="modal-overlay" id="modalReset">
            <div class="modal-box">
                <div class="modal-icon-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="modal-title-danger">Reset Data Vote?</h3>
                <p class="modal-desc">
                    Semua suara yang sudah masuk untuk periode
                    <strong>{{ $periodeTerpilih->nama_periode }}</strong> akan dihapus permanen dan tidak dapat dikembalikan.
                </p>
                <form action="{{ route('admin.hasil.reset', $periodeTerpilih->id) }}" method="POST">
                    @csrf
                    <div class="modal-actions">
                        <button type="submit" class="btn-danger-solid">Ya, Reset</button>
                        <button type="button" class="btn-cancel-outline" onclick="tutupModalReset()">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const labelsChart = [
                @foreach ($hasilPerKandidat as $item)
                    "No. {{ $item->kandidat->no_urut }}",
                @endforeach
            ];
            const dataChart = [
                @foreach ($hasilPerKandidat as $item)
                    {{ $item->suara }},
                @endforeach
            ];
            const warnaChart = ['#2D6BA0', '#38BDF8', '#4ADE80', '#C084FC', '#F59E0B', '#F472B6'];

            let chartInstance = null;

            function renderChart(type) {
                if (chartInstance) chartInstance.destroy();
                const ctx = document.getElementById('chartHasilVoting').getContext('2d');
                chartInstance = new Chart(ctx, {
                    type: type,
                    data: {
                        labels: labelsChart,
                        datasets: [{
                            label: 'Jumlah Suara',
                            data: dataChart,
                            backgroundColor: warnaChart,
                            borderRadius: type === 'bar' ? 8 : 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: type === 'pie' } },
                        scales: type === 'bar' ? { y: { beginAtZero: true, ticks: { precision: 0 } } } : {}
                    }
                });
            }

            function gantiChart(type) {
                document.getElementById('btnChartBar').classList.toggle('selected', type === 'bar');
                document.getElementById('btnChartPie').classList.toggle('selected', type === 'pie');
                renderChart(type);
            }

            function bukaModalReset() {
                document.getElementById('modalReset').style.display = 'flex';
            }
            function tutupModalReset() {
                document.getElementById('modalReset').style.display = 'none';
            }

            renderChart('bar');
        </script>
    @endif
</div>
@endsection
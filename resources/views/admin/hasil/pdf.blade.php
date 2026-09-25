<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1E293B; }
        h1 { font-size: 16px; color: #14406B; margin-bottom: 2px; }
        p { margin: 0 0 12px; color: #64748B; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #CBD5E1; padding: 6px 8px; text-align: left; }
        th { background: #F1F5F9; }
        .summary { margin-bottom: 14px; }
        .summary span { display: inline-block; margin-right: 20px; }
        .chart-wrap { text-align: center; margin: 16px 0; }
        .chart-wrap img { width: 400px; }
    </style>
</head>
<body>
    <h1>Laporan Hasil Voting</h1>
    <p>{{ $periodeTerpilih->nama_periode }} ({{ $periodeTerpilih->tanggal_mulai }} &ndash; {{ $periodeTerpilih->tanggal_selesai }})</p>

    <div class="summary">
        <span><strong>Total Suara Masuk:</strong> {{ $totalSuara }}</span>
        <span><strong>Sudah Voting:</strong> {{ $sudahVoting }}</span>
        <span><strong>Belum Voting:</strong> {{ $belumVoting }}</span>
        <span><strong>Partisipasi:</strong> {{ $partisipasi }}%</span>
    </div>

    <div class="chart-wrap">
        <img src="{{ $chartUrl }}">
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kandidat</th>
                <th>Kelas</th>
                <th>Suara</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilPerKandidat as $item)
                <tr>
                    <td>{{ $item->kandidat->no_urut }}</td>
                    <td>{{ $item->kandidat->nama_ketua }} &amp; {{ $item->kandidat->nama_wakil }}</td>
                    <td>{{ $item->kandidat->kelas_ketua }}</td>
                    <td>{{ $item->suara }}</td>
                    <td>{{ $item->persen }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
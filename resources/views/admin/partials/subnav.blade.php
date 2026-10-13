@php
    // Sub-tab berubah mengikuti bagian yang sedang dibuka: Dashboard (konten website) atau Pemilihan (e-voting)
    $modePemilihan = request()->routeIs('admin.dashboard', 'admin.siswa.*', 'admin.kandidat.*', 'admin.periode.*', 'admin.hasil.*', 'admin.belumMemilih');

    if ($modePemilihan) {
        $tab = [
            ['admin.dashboard',      'admin.dashboard',   'Ringkasan'],
            ['admin.siswa.index',    'admin.siswa.*',     'Data Siswa'],
            ['admin.kandidat.index', 'admin.kandidat.*',  'Kandidat'],
            ['admin.periode.index',  'admin.periode.*',   'Periode'],
            ['admin.hasil.index',    'admin.hasil.*',     'Hasil Voting'],
            ['admin.belumMemilih',   'admin.belumMemilih','Belum Memilih'],
        ];
    } else {
        $tab = [
            ['admin.beranda',             'admin.beranda*',        'Beranda'],
            ['admin.tentang',             'admin.tentang*',        'Tentang OSIS'],
            ['admin.program-kerja.index', 'admin.program-kerja.*', 'Program Kerja'],
            ['admin.acara.index',         'admin.acara.*',         'Acara'],
            ['admin.galeri.index',        'admin.galeri.*',        'Galeri'],
            ['admin.kontak',              'admin.kontak*',         'Kontak'],
        ];
    }
@endphp
<nav class="subnav" aria-label="Menu {{ $modePemilihan ? 'pemilihan' : 'konten website' }}">
    @foreach($tab as [$rt, $pola, $label])
        <a href="{{ route($rt) }}" class="{{ request()->routeIs($pola) ? 'on' : '' }}">{{ $label }}</a>
    @endforeach
</nav>
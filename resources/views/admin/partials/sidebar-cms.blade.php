@php
    $pemilihanAktif = request()->routeIs('admin.dashboard', 'admin.siswa.*', 'admin.kandidat.*', 'admin.periode.*', 'admin.hasil.*', 'admin.belumMemilih');
    $kontenAktif    = request()->routeIs('admin.pengaturan.*', 'admin.program-kerja.*', 'admin.acara.*');
@endphp

<div class="side-brand">
    <img src="{{ asset('image/logo-osis.png') }}" alt="Logo">
    <div><b>OSIS SMK</b><small>Pertiwi Kuningan</small></div>
</div>

<nav class="side-nav">
    <a href="{{ route('admin.beranda') }}" class="{{ request()->routeIs('admin.beranda') ? 'on' : '' }}">🏠 Dashboard</a>

    <details {{ $kontenAktif ? 'open' : '' }}>
        <summary>📝 Konten Website</summary>
        <a href="{{ route('admin.pengaturan.index') }}" class="{{ request()->routeIs('admin.pengaturan.*') ? 'on' : '' }}">Pengaturan Beranda</a>
        <a href="{{ route('admin.program-kerja.index') }}" class="{{ request()->routeIs('admin.program-kerja.*') ? 'on' : '' }}">Program Kerja</a>
        <a href="{{ route('admin.acara.index') }}" class="{{ request()->routeIs('admin.acara.*') ? 'on' : '' }}">Acara</a>
    </details>

    <details {{ $pemilihanAktif ? 'open' : '' }}>
        <summary>🗳️ Pemilihan</summary>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">Ringkasan Pemilihan</a>
        <a href="{{ route('admin.siswa.index') }}" class="{{ request()->routeIs('admin.siswa.*') ? 'on' : '' }}">Kelola Data Siswa</a>
        <a href="{{ route('admin.kandidat.index') }}" class="{{ request()->routeIs('admin.kandidat.*') ? 'on' : '' }}">Kelola Kandidat</a>
        <a href="{{ route('admin.periode.index') }}" class="{{ request()->routeIs('admin.periode.*') ? 'on' : '' }}">Kelola Periode</a>
        <a href="{{ route('admin.hasil.index') }}" class="{{ request()->routeIs('admin.hasil.*') ? 'on' : '' }}">Hasil Voting</a>
        <a href="{{ route('admin.belumMemilih') }}" class="{{ request()->routeIs('admin.belumMemilih') ? 'on' : '' }}">Belum Memilih</a>
    </details>

    <a href="{{ route('landing') }}" target="_blank">🌐 Lihat Website</a>
</nav>

<div class="side-foot">
    <a href="{{ route('admin.logout') }}">⎋ Logout</a>
</div>
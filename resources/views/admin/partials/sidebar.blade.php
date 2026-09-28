<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('image/logo-osis.png') }}" alt="Logo">
        <div class="name">OSIS SMK<span>Pertiwi Kuningan</span></div>

        <!-- Tombol tutup menu: hanya tampil di HP -->
        <button type="button" class="sidebar-close" onclick="tutupSidebar()" aria-label="Tutup menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="sidebar-menu">
        <a href="{{ route('admin.siswa.index') }}" class="{{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> Kelola Data Siswa
        </a>
        <a href="{{ route('admin.kandidat.index') }}" class="{{ request()->routeIs('admin.kandidat*') ? 'active' : '' }}">
            <i class="fa-solid fa-id-badge"></i> Kelola Kandidat
        </a>
        <a href="{{ route('admin.periode.index') }}" class="{{ request()->routeIs('admin.periode*') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-days"></i> Kelola Periode
        </a>
        <a href="{{ route('admin.hasil.index') }}" class="{{ request()->routeIs('admin.hasil*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-simple"></i> Hasil Voting
        </a>
        <a href="{{ route('admin.belumMemilih') }}" class="{{ request()->routeIs('admin.belumMemilih') ? 'active' : '' }}">
            <i class="fa-solid fa-user-clock"></i> Belum Memilih
        </a>
    </div>

    <div class="sidebar-logout">
        <a href="#" onclick="event.preventDefault(); bukaModalLogout();">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</div>
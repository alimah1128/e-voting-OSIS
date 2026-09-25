@extends('layouts.admin')

@section('title', 'Kelola Periode')

@section('content')
<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>

    <div class="header-action">
        <div>
            <h2>Kelola Periode</h2>
            <p>Atur periode pemilihan OSIS</p>
        </div>
        <button type="button" class="btn-primary" onclick="bukaModalTambah()">+ Tambah Periode</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Periode</th>
                <th>Tahun Ajaran</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($periodes as $periode)
                <tr>
                    <td><strong>{{ $periode->nama_periode }}</strong></td>
                    <td>{{ $periode->tahun_ajaran }}</td>
                    <td>{{ $periode->tanggal_mulai }}</td>
                    <td>{{ $periode->tanggal_selesai }}</td>
                    <td><span class="badge badge-{{ $periode->status }}">{{ ucfirst($periode->status) }}</span></td>
                    <td>
                        <button type="button" class="btn-edit" onclick='bukaModalEdit(@json($periode))'>Edit</button>
                        <button type="button" class="btn-status-action" onclick="bukaModalStatus({{ $periode->id }}, '{{ addslashes($periode->nama_periode) }}', '{{ $periode->status }}')">Status</button>
                        <button type="button" class="btn-delete" onclick="bukaModalHapus({{ $periode->id }}, '{{ addslashes($periode->nama_periode) }}')">Hapus</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada data periode.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH/EDIT PERIODE -->
<div class="modal-overlay" id="modalPeriode">
    <div class="modal-box" style="text-align:left;">
        <h3 class="modal-form-title" id="modalPeriodeTitle">Tambah Periode Pemilihan</h3>

        <form id="formPeriode" method="POST">
            @csrf
            <input type="hidden" name="_method" id="methodOverridePeriode" value="POST">

            <div class="field-group">
                <label>Nama Periode</label>
                <input type="text" name="nama_periode" id="input_nama_periode" class="plain-input" placeholder="cth. Pemilihan OSIS 2025/2026">
            </div>

            <div class="field-group">
                <label>Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" id="input_tahun_ajaran" class="plain-input" placeholder="cth. 2026/2027">
            </div>

            <div class="field-group">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="input_tanggal_mulai" class="plain-input">
            </div>

            <div class="field-group">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="input_tanggal_selesai" class="plain-input">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" id="btnSubmitPeriode">Simpan Periode</button>
                <button type="button" class="btn-cancel" onclick="tutupModalPeriode()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL SET STATUS -->
<div class="modal-overlay" id="modalStatus">
    <div class="modal-box" style="text-align:left;">
        <h3 class="modal-form-title">Set Status Periode</h3>
        <p class="modal-desc" id="statusPeriodeNama" style="margin-top:-10px;"></p>

        <div class="status-select-group">
            <button type="button" class="status-select-btn" data-status="draft" onclick="pilihStatus(this, 'draft')">Draft</button>
            <button type="button" class="status-select-btn" data-status="active" onclick="pilihStatus(this, 'active')">Active</button>
            <button type="button" class="status-select-btn" data-status="inactive" onclick="pilihStatus(this, 'inactive')">Inactive</button>
        </div>

        <form id="formStatus" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" id="inputStatusTerpilih" value="draft">
            <div class="form-actions">
                <button type="submit" class="btn-primary">✓ Terapkan Status</button>
                <button type="button" class="btn-cancel" onclick="tutupModalStatus()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL HAPUS PERIODE -->
<div class="modal-overlay" id="modalHapusPeriode">
    <div class="modal-box">
        <div class="modal-icon-warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 class="modal-title-danger" style="color:#1E293B;">Hapus Periode?</h3>
        <p class="modal-desc">
            Yakin ingin menghapus periode:<br>
            <strong id="hapusPeriodeNama" style="color:#2D6BA0;"></strong>
        </p>
        <div class="modal-warning-box">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><strong>Perhatian:</strong> Semua data kandidat dan hasil vote yang terkait dengan periode ini akan ikut terhapus secara permanen.</span>
        </div>
        <form id="formHapusPeriode" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="submit" class="btn-danger-solid">Ya, Hapus</button>
                <button type="button" class="btn-cancel-outline" onclick="tutupModalHapusPeriode()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    const routeStorePeriode = "{{ route('admin.periode.store') }}";

    function bukaModalTambah() {
        document.getElementById('formPeriode').reset();
        document.getElementById('modalPeriodeTitle').textContent = 'Tambah Periode Pemilihan';
        document.getElementById('formPeriode').action = routeStorePeriode;
        document.getElementById('methodOverridePeriode').value = 'POST';
        document.getElementById('btnSubmitPeriode').textContent = 'Simpan Periode';
        document.getElementById('modalPeriode').style.display = 'flex';
    }

    function bukaModalEdit(periode) {
        document.getElementById('modalPeriodeTitle').textContent = 'Edit Periode Pemilihan';
        document.getElementById('formPeriode').action = '/admin/periode/' + periode.id;
        document.getElementById('methodOverridePeriode').value = 'PUT';
        document.getElementById('btnSubmitPeriode').textContent = 'Simpan Periode';

        document.getElementById('input_nama_periode').value = periode.nama_periode;
        document.getElementById('input_tahun_ajaran').value = periode.tahun_ajaran;
        document.getElementById('input_tanggal_mulai').value = periode.tanggal_mulai;
        document.getElementById('input_tanggal_selesai').value = periode.tanggal_selesai;

        document.getElementById('modalPeriode').style.display = 'flex';
    }

    function tutupModalPeriode() {
        document.getElementById('modalPeriode').style.display = 'none';
    }

    function pilihStatus(btn, status) {
        document.querySelectorAll('.status-select-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('inputStatusTerpilih').value = status;
    }

    function bukaModalStatus(id, nama, statusSekarang) {
        document.getElementById('statusPeriodeNama').textContent = nama;
        document.getElementById('formStatus').action = '/admin/periode/' + id + '/status';
        document.getElementById('inputStatusTerpilih').value = statusSekarang;

        document.querySelectorAll('.status-select-btn').forEach(b => {
            b.classList.toggle('selected', b.dataset.status === statusSekarang);
        });

        document.getElementById('modalStatus').style.display = 'flex';
    }

    function tutupModalStatus() {
        document.getElementById('modalStatus').style.display = 'none';
    }

    function bukaModalHapus(id, nama) {
        document.getElementById('hapusPeriodeNama').textContent = nama;
        document.getElementById('formHapusPeriode').action = '/admin/periode/' + id;
        document.getElementById('modalHapusPeriode').style.display = 'flex';
    }

    function tutupModalHapusPeriode() {
        document.getElementById('modalHapusPeriode').style.display = 'none';
    }
</script>
@endsection
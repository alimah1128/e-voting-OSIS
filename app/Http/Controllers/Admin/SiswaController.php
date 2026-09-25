<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\PeriodePemilihan;
use App\Imports\SiswaImport;
use App\Exports\SiswaTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Tampilkan daftar data siswa + Pencarian & Filter
     */
    public function index(Request $request)
    {
        // Ambil periode yang sedang aktif, buat ngecek status voting tiap siswa.
        // Kalau tidak ada periode aktif, kolom status voting akan tampil "-".
        $periodeAktif = PeriodePemilihan::where('status', 'active')->first();

        // Daftar ID siswa yang sudah voting di periode aktif (kosong kalau tidak ada periode aktif)
        $sudahVotingIds = collect();
        if ($periodeAktif) {
            $sudahVotingIds = DB::table('votes')
                ->where('periode_id', $periodeAktif->id)
                ->pluck('siswa_id');
        }

        $query = Siswa::query();

        // Fitur Pencarian (Nama, NIS, Username)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Kelas
        if ($request->filled('kelas') && $request->kelas != 'Semua') {
            $query->where('kelas', $request->kelas);
        }

        // Fitur Filter Status Voting (cuma jalan kalau ada periode aktif)
        if ($periodeAktif && $request->filled('status_voting') && $request->status_voting != 'Semua') {
            if ($request->status_voting === 'sudah') {
                $query->whereIn('id', $sudahVotingIds);
            } elseif ($request->status_voting === 'belum') {
                $query->whereNotIn('id', $sudahVotingIds);
            }
        }

        // Ambil data dengan pagination (5 item per halaman)
        $siswas = $query->paginate(20)->withQueryString();

        // Total keseluruhan siswa (tanpa filter), untuk modal "Hapus Semua"
        $totalSiswa = Siswa::count();

        // Daftar kelas OTOMATIS dari data siswa yang ada di database.
        // Kalau nanti ada kelas baru masuk lewat import/tambah manual,
        // otomatis muncul di dropdown filter tanpa perlu edit kode.
        $daftarKelas = Siswa::select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        return view('admin.siswa.index', compact(
            'siswas', 'totalSiswa', 'daftarKelas', 'periodeAktif', 'sudahVotingIds'
        ));
    }

    /**
     * Tampilkan form tambah siswa (opsional jika menggunakan modal di index)
     */
    public function create()
    {
        return view('admin.siswa.create');
    }

    /**
     * Simpan data siswa baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis'           => 'required|unique:siswas,nis',
            'nama'          => 'required|string|max:255',
            'kelas'         => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'username'      => 'required|unique:siswas,username',
            'password'      => 'required|min:6',
        ]);

        Siswa::create([
            'nis'           => $request->nis,
            'nama'          => $request->nama,
            'kelas'         => $request->kelas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail siswa (jika diperlukan)
     */
    public function show(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Tampilkan form edit siswa
     */
    public function edit(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.siswa.edit', compact('siswa'));
    }

    /**
     * Update data siswa
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis'           => 'required|unique:siswas,nis,' . $siswa->id,
            'nama'          => 'required|string|max:255',
            'kelas'         => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'username'      => 'required|unique:siswas,username,' . $siswa->id,
        ]);

        $data = [
            'nis'           => $request->nis,
            'nama'          => $request->nama,
            'kelas'         => $request->kelas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'username'      => $request->username,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $siswa->update($data);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Hapus 1 data siswa
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Hapus Seluruh Data Siswa (Fitur Tombol Merah "Hapus Semua")
     */
    public function deleteAll()
    {
        Siswa::query()->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Seluruh data siswa berhasil dihapus.');
    }

    /**
     * Import data siswa dari file Excel/CSV
     */
        public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new SiswaImport;

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return redirect()->route('admin.siswa.index')
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }

        $jumlahGagal = count($import->failures());

        if ($jumlahGagal > 0) {
            $pesanGagal = collect($import->failures())
                ->map(fn ($f) => 'Baris ' . $f->row() . ': ' . implode(', ', $f->errors()))
                ->implode(' | ');

            return redirect()->route('admin.siswa.index')
                ->with('warning', "Import selesai, tapi {$jumlahGagal} baris dilewati. {$pesanGagal}");
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diimport.');
    }

    /**
     * Download template Excel untuk import data siswa
     */
    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport, 'template_siswa.xlsx');
    }
}
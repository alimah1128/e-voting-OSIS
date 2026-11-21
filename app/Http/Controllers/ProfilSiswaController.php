<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Services\BlobStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilSiswaController extends Controller
{
    /**
     * Tampilkan halaman profil siswa yang sedang login (read-only)
     */
    public function index()
    {
        $siswaAktif = Siswa::findOrFail(session('siswa_id'));
        $siswa = $siswaAktif;

        return view('profil-siswa', compact('siswaAktif', 'siswa'));
    }

    /**
     * Tampilkan form edit profil (foto & password)
     */
    public function edit()
    {
        $siswaAktif = Siswa::findOrFail(session('siswa_id'));
        $siswa = $siswaAktif;

        return view('edit-profil', compact('siswaAktif', 'siswa'));
    }

    /**
     * Simpan perubahan profil (foto, hapus foto, & password)
     */
    public function update(Request $request)
    {
        $siswa = Siswa::findOrFail(session('siswa_id'));

        $request->validate([
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'password'   => 'nullable|min:6',
            'hapus_foto' => 'nullable|in:0,1',
        ], [
            'foto.image'   => 'File foto harus berupa gambar.',
            'foto.mimes'   => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max'     => 'Ukuran foto maksimal 4 MB.',
            'password.min' => 'Password baru minimal 6 karakter.',
        ]);

        $data = [];
        $lama = $siswa->foto;

        if ($request->hasFile('foto')) {
            // Ganti dengan foto baru: unggah dulu, file lama dihapus setelah DB diperbarui
            $data['foto'] = app(BlobStorage::class)->upload($request->file('foto'), 'siswa');
        } elseif ($request->input('hapus_foto') === '1') {
            // Tombol X ditekan dan tidak ada foto baru: kolom dikosongkan, file lama dihapus setelahnya
            $data['foto'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if (! empty($data)) {
            $siswa->update($data);

            if (array_key_exists('foto', $data)) $this->hapusFileFoto($lama);
        }

        return redirect()->route('siswa.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    /** Hapus file foto lama dari Blob (kalau ada) */
    private function hapusFileFoto(?string $namaFile): void
    {
        app(BlobStorage::class)->hapus($namaFile);
    }
}
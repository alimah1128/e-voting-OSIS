<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
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
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'   => 'nullable|min:6',
            'hapus_foto' => 'nullable|in:0,1',
        ], [
            'foto.image'   => 'File foto harus berupa gambar.',
            'foto.mimes'   => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max'     => 'Ukuran foto maksimal 2 MB.',
            'password.min' => 'Password baru minimal 6 karakter.',
        ]);

        $data = [];

        if ($request->hasFile('foto')) {
            // Ganti dengan foto baru: hapus file lama dulu
            $this->hapusFileFoto($siswa->foto);

            $file = $request->file('foto');
            $filename = 'siswa_' . $siswa->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('image'), $filename);

            $data['foto'] = $filename;
        } elseif ($request->input('hapus_foto') === '1') {
            // Tombol X ditekan dan tidak ada foto baru: hapus foto, kolom dikosongkan
            $this->hapusFileFoto($siswa->foto);

            $data['foto'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if (! empty($data)) {
            $siswa->update($data);
        }

        return redirect()->route('siswa.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Hapus file foto dari folder public/image (kalau ada)
     */
    private function hapusFileFoto(?string $namaFile): void
    {
        if ($namaFile && file_exists(public_path('image/' . $namaFile))) {
            unlink(public_path('image/' . $namaFile));
        }
    }
}
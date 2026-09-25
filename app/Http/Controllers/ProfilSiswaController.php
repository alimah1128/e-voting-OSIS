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
     * Simpan perubahan profil (foto & password)
     */
    public function update(Request $request)
    {
        $siswa = Siswa::findOrFail(session('siswa_id'));

        $request->validate([
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [];

        if ($request->hasFile('foto')) {
            if ($siswa->foto && file_exists(public_path('image/' . $siswa->foto))) {
                unlink(public_path('image/' . $siswa->foto));
            }

            $file = $request->file('foto');
            $filename = 'siswa_' . $siswa->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('image'), $filename);

            $data['foto'] = $filename;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if (! empty($data)) {
            $siswa->update($data);
        }

        return redirect()->route('siswa.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
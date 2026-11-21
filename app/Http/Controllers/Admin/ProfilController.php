<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BlobStorage;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    public function update(Request $request)
    {
        $admin = Admin::findOrFail(session('admin_id'));

        $request->validateWithBag('profilForm', [
            'nama_lengkap' => 'nullable|string|max:255',
            'username' => 'required|string|unique:admins,username,' . $admin->id,
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $lama = $admin->foto;

        if ($request->hasFile('foto')) {
            // Ganti foto: unggah dulu, foto lama dihapus setelah DB diperbarui
            $data['foto'] = app(BlobStorage::class)->upload($request->file('foto'), 'admin');

        } elseif ($request->boolean('hapus_foto') && $admin->foto) {
            // Ditandai "hapus foto" (tanpa pilih foto baru): hapus file lama, kosongkan kolomnya
            $data['foto'] = null;
        }

        $admin->update($data);

        if (array_key_exists('foto', $data)) app(BlobStorage::class)->hapus($lama);

        session([
            'admin_username' => $admin->username,
            'admin_nama' => $admin->nama_lengkap,
            'admin_foto' => $admin->foto,
        ]);

        return redirect()->back()->with('success_profil', 'Profil berhasil diperbarui.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Ganti foto: hapus foto lama, pasang foto baru
            if ($admin->foto && file_exists(public_path('image/' . $admin->foto))) {
                unlink(public_path('image/' . $admin->foto));
            }

            $file = $request->file('foto');
            $filename = 'admin_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('image'), $filename);
            $data['foto'] = $filename;

        } elseif ($request->boolean('hapus_foto') && $admin->foto) {
            // Ditandai "hapus foto" (tanpa pilih foto baru): hapus file lama, kosongkan kolomnya
            if (file_exists(public_path('image/' . $admin->foto))) {
                unlink(public_path('image/' . $admin->foto));
            }
            $data['foto'] = null;
        }

        $admin->update($data);

        session([
            'admin_username' => $admin->username,
            'admin_nama' => $admin->nama_lengkap,
            'admin_foto' => $admin->foto,
        ]);

        return redirect()->back()->with('success_profil', 'Profil berhasil diperbarui.');
    }
}
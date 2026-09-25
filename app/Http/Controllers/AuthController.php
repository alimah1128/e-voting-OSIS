<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        // Validasi input tidak boleh kosong
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cari siswa berdasarkan username
        $siswa = DB::table('siswas')->where('username', $request->username)->first();

        // Cek apakah siswa ditemukan DAN password cocok
        if ($siswa && Hash::check($request->password, $siswa->password)) {
            // Simpan data siswa ke session (biar "diinget" statusnya sudah login)
            session([
                'siswa_id' => $siswa->id,
                'siswa_nama' => $siswa->nama,
                'siswa_kelas' => $siswa->kelas,
            ]);

            return redirect('/dashboard');
        }

        // Kalau username/password salah
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    // Logout
    public function logout(Request $request)
    {
        session()->forget(['siswa_id', 'siswa_nama', 'siswa_kelas']);
        return redirect('/login');
    }
}
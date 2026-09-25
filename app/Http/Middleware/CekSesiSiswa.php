<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CekSesiSiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('siswa_id')) {
            return redirect('/login')->withErrors(['username' => 'Silakan login terlebih dahulu.']);
        }

        // Ambil data siswa TERBARU dari database (bukan dari session yang bisa basi),
        // supaya kalau admin mengubah data siswa (nama/kelas/dll), navbar & profil
        // langsung ikut update tanpa perlu siswa logout-login lagi.
        $siswaAktif = DB::table('siswas')->where('id', session('siswa_id'))->first();

        if (!$siswaAktif) {
            session()->forget(['siswa_id', 'siswa_nama', 'siswa_kelas']);
            return redirect('/login')->withErrors(['username' => 'Akun tidak ditemukan, silakan login kembali.']);
        }

        // Sediakan ke semua view lewat variabel $siswaAktif
        View::share('siswaAktif', $siswaAktif);

        return $next($request);
    }
}
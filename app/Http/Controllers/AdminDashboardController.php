<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total jumlah siswa & kandidat
        $jumlahSiswa = DB::table('siswas')->count();
        $jumlahKandidat = DB::table('kandidat')->count();

        // Ambil periode yang sedang aktif (kalau ada)
        $periodeAktif = DB::table('periode_pemilihan')
            ->where('status', 'active')
            ->first();

        // Hitung siswa yang sudah voting di periode aktif
        $sudahVoting = 0;
        if ($periodeAktif) {
            $sudahVoting = DB::table('votes')
                ->where('periode_id', $periodeAktif->id)
                ->distinct('siswa_id')
                ->count('siswa_id');
        }
        $belumVoting = $jumlahSiswa - $sudahVoting;

        // Semua periode, diurutkan dari yang terbaru
        $semuaPeriode = DB::table('periode_pemilihan')
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('admin.dashboard', compact(
            'jumlahSiswa',
            'jumlahKandidat',
            'periodeAktif',
            'sudahVoting',
            'belumVoting',
            'semuaPeriode'
        ));
    }
}
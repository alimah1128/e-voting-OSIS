<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total jumlah siswa (semua siswa, tidak tergantung periode)
        $jumlahSiswa = DB::table('siswas')->count();

        // Ambil periode yang sedang aktif (kalau ada)
        $periodeAktif = DB::table('periode_pemilihan')
            ->where('status', 'active')
            ->first();

        // Statistik hanya dihitung untuk periode aktif.
        // Kalau belum ada periode aktif, semuanya 0.
        $jumlahKandidat = 0;
        $sudahVoting = 0;
        $belumVoting = 0;

        if ($periodeAktif) {
            // Kandidat milik periode aktif saja
            $jumlahKandidat = DB::table('kandidat')
                ->where('periode_id', $periodeAktif->id)
                ->count();

            // Siswa yang sudah voting di periode aktif
            $sudahVoting = DB::table('votes')
                ->where('periode_id', $periodeAktif->id)
                ->distinct('siswa_id')
                ->count('siswa_id');

            $belumVoting = $jumlahSiswa - $sudahVoting;
        }

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
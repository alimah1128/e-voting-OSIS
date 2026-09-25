<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pasangan kandidat
        $jumlahKandidat = DB::table('kandidat')->count();

        // Ambil periode yang sedang aktif (kalau ada)
        $periodeAktif = DB::table('periode_pemilihan')
            ->where('status', 'active')
            ->first();

        $statusVotingLabel = 'Belum Ada Periode';
        $statusVotingSub = 'Belum ada voting yang berjalan';
        $sudahVoting = false;
        $periodeTeks = '-';
        $periodeSub = '';
        $sisaWaktuTeks = null;
        $tahunAjaran = 'OSIS'; // fallback kalau belum ada periode aktif sama sekali

        if ($periodeAktif) {
            // Cek apakah siswa yang sedang login sudah vote di periode ini
            $sudahVoting = DB::table('votes')
                ->where('periode_id', $periodeAktif->id)
                ->where('siswa_id', session('siswa_id'))
                ->exists();

            $statusVotingLabel = 'Sedang Berlangsung';
            $statusVotingSub = $sudahVoting ? 'Suara Anda sudah masuk' : 'Suara Anda belum masuk';

            $mulai = Carbon::parse($periodeAktif->tanggal_mulai);
            $selesai = Carbon::parse($periodeAktif->tanggal_selesai)->endOfDay();

            $periodeTeks = $mulai->format('j M') . ' – ' . $selesai->format('j M Y');
            $mingguDurasi = max(1, round($mulai->diffInDays($selesai) / 7));
            $periodeSub = 'Periode ' . $mingguDurasi . ' Minggu';

            $now = Carbon::now();
            if ($now->lessThan($selesai)) {
                $diff = $now->diff($selesai);
                $sisaWaktuTeks = $diff->days . ' hari ' . $diff->h . ' jam lagi';
            } else {
                $sisaWaktuTeks = 'Waktu voting telah berakhir';
            }

            // Kalau tahun_ajaran belum diisi admin, fallback pakai tahun dari tanggal mulai
            $tahunAjaran = $periodeAktif->tahun_ajaran ?? $mulai->format('Y');
        }

        return view('dashboard', compact(
            'jumlahKandidat',
            'periodeAktif',
            'statusVotingLabel',
            'statusVotingSub',
            'sudahVoting',
            'periodeTeks',
            'periodeSub',
            'sisaWaktuTeks',
            'tahunAjaran'
        ));
    }
}
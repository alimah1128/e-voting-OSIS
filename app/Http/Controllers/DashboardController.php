<?php

namespace App\Http\Controllers;

use App\Support\PeriodeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // aktif() otomatis menonaktifkan periode yang tanggal selesainya sudah lewat
        $periodeAktif = PeriodeHelper::aktif();

        // Default (tidak ada periode Active: draft / inactive / belum dibuat) -> semuanya kosong
        $jumlahKandidat = 0;
        $statusVotingLabel = 'Belum Ada Periode';
        $statusVotingSub = 'Belum ada periode terbaru';
        $sudahVoting = false;
        $votingBuka = false;
        $statusWaktu = 'tidak_ada';
        $periodeTeks = '-';
        $periodeSub = '';
        $sisaWaktuTeks = null;
        $tahunAjaran = 'OSIS'; // fallback kalau belum ada periode sama sekali

        if ($periodeAktif) {
            $mulai = Carbon::parse($periodeAktif->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($periodeAktif->tanggal_selesai)->endOfDay();
            $now = Carbon::now();

            $statusWaktu = PeriodeHelper::statusWaktu($periodeAktif);
            $votingBuka = $statusWaktu === 'berlangsung';

            $periodeTeks = $mulai->format('j M') . ' – ' . $selesai->format('j M Y');
            $mingguDurasi = max(1, round($mulai->diffInDays($selesai) / 7));
            $periodeSub = 'Periode ' . $mingguDurasi . ' Minggu';

            // Kalau tahun_ajaran belum diisi admin, fallback pakai tahun dari tanggal mulai
            $tahunAjaran = $periodeAktif->tahun_ajaran ?? $mulai->format('Y');

            if ($votingBuka) {
                $sudahVoting = DB::table('votes')
                    ->where('periode_id', $periodeAktif->id)
                    ->where('siswa_id', session('siswa_id'))
                    ->exists();

                $jumlahKandidat = DB::table('kandidat')
                    ->where('periode_id', $periodeAktif->id)
                    ->count();

                $statusVotingLabel = 'Sedang Berlangsung';
                $statusVotingSub = $sudahVoting ? 'Suara Anda sudah masuk' : 'Suara Anda belum masuk';

                $diff = $now->diff($selesai);
                $sisaWaktuTeks = $diff->days . ' hari ' . $diff->h . ' jam lagi';
            } elseif ($statusWaktu === 'belum_mulai') {
                $statusVotingLabel = 'Belum Dimulai';
                $statusVotingSub = 'Voting belum dibuka';
                $sisaWaktuTeks = 'Voting belum dimulai';
            } else {
                $statusVotingLabel = 'Voting Ditutup';
                $statusVotingSub = 'Waktu voting telah berakhir';
                $sisaWaktuTeks = 'Waktu voting telah berakhir';
            }
        } else {
            // Tidak ada periode Active: tanggal periode lama TIDAK ditampilkan,
            // periode terbaru dipakai hanya untuk teks tahun ajaran di banner & navbar
            $terbaru = PeriodeHelper::terbaru();
            if ($terbaru) {
                $tahunAjaran = $terbaru->tahun_ajaran ?? Carbon::parse($terbaru->tanggal_mulai)->format('Y');
            }
        }

        return view('dashboard', compact(
            'jumlahKandidat',
            'periodeAktif',
            'statusVotingLabel',
            'statusVotingSub',
            'sudahVoting',
            'votingBuka',
            'statusWaktu',
            'periodeTeks',
            'periodeSub',
            'sisaWaktuTeks',
            'tahunAjaran'
        ));
    }
}
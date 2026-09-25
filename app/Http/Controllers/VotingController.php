<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VotingController extends Controller
{
    // Helper: ambil tahun ajaran dari periode aktif, sama seperti di DashboardController
    private function tahunAjaranDari($periode)
    {
        if (! $periode) {
            return 'OSIS';
        }
        return $periode->tahun_ajaran ?? Carbon::parse($periode->tanggal_mulai)->format('Y');
    }

    // Halaman daftar kandidat (GET /kandidat)
    public function daftarKandidat()
    {
        $periodeAktif = DB::table('periode_pemilihan')->where('status', 'active')->first();

        $kandidats = collect();
        $sudahVoting = false;

        if ($periodeAktif) {
            $kandidats = DB::table('kandidat')
                ->where('periode_id', $periodeAktif->id)
                ->orderBy('no_urut')
                ->get()
                ->map(function ($k) {
                    $k->misi = json_decode($k->misi, true) ?? [];
                    $k->program_kerja = json_decode($k->program_kerja, true) ?? [];
                    return $k;
                });

            $sudahVoting = DB::table('votes')
                ->where('periode_id', $periodeAktif->id)
                ->where('siswa_id', session('siswa_id'))
                ->exists();
        }

        $tahunAjaran = $this->tahunAjaranDari($periodeAktif);

        return view('kandidat', compact('periodeAktif', 'kandidats', 'sudahVoting', 'tahunAjaran'));
    }

    // Halaman konfirmasi pilihan (GET /konfirmasi/{id})
    public function konfirmasi(string $id)
    {
        $kandidat = DB::table('kandidat')->where('id', $id)->first();

        if (! $kandidat) {
            return redirect()->route('kandidat')->withErrors(['pilihan' => 'Kandidat tidak ditemukan.']);
        }

        $kandidat->misi = json_decode($kandidat->misi, true) ?? [];
        $kandidat->program_kerja = json_decode($kandidat->program_kerja, true) ?? [];

        $periodeAktif = DB::table('periode_pemilihan')->where('id', $kandidat->periode_id)->first();
        $tahunAjaran = $this->tahunAjaranDari($periodeAktif);

        return view('konfirmasi', compact('kandidat', 'tahunAjaran'));
    }

    // Proses submit vote (POST /submit-vote)
    public function submitVote(Request $request)
    {
        $request->validate([
            'kandidat_id' => 'required|exists:kandidat,id',
        ]);

        $kandidat = DB::table('kandidat')->where('id', $request->kandidat_id)->first();

        $sudahVoting = DB::table('votes')
            ->where('periode_id', $kandidat->periode_id)
            ->where('siswa_id', session('siswa_id'))
            ->exists();

        if ($sudahVoting) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah melakukan voting untuk periode ini.');
        }

        DB::table('votes')->insert([
            'periode_id' => $kandidat->periode_id,
            'siswa_id' => session('siswa_id'),
            'kandidat_id' => $kandidat->id,
            'waktu_vote' => now(),
        ]);

        return redirect()->route('selesai');
    }

    // Halaman Voting Berhasil (GET /selesai)
    public function selesai()
    {
        $periodeAktif = DB::table('periode_pemilihan')->where('status', 'active')->first();
        $tahunAjaran = $this->tahunAjaranDari($periodeAktif);

        return view('selesai', compact('tahunAjaran'));
    }
}
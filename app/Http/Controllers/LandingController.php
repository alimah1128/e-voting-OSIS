<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Galeri;
use App\Models\Pengaturan;
use App\Models\ProgramKerja;

class LandingController extends Controller
{
    /** Beranda: hero + ringkasan (3 program, 3 acara terbaru, galeri) */
    public function index()
    {
        $p = Pengaturan::semua();

        $programs = ProgramKerja::where('aktif', true)->orderBy('urutan')->take(3)->get();
        $acaras   = Acara::where('aktif', true)->orderByDesc('tanggal')->take(3)->get();
        $galeri   = Galeri::orderByDesc('id')->take(8)->get();

        return view('landing.index', compact('p', 'programs', 'acaras', 'galeri'));
    }

    /** Halaman Tentang: profil, visi, misi */
    public function tentang()
    {
        $p    = Pengaturan::semua();
        $misi = $this->pecahMisi($p);

        return view('landing.tentang', compact('p', 'misi'));
    }

    /** Halaman Program Kerja: semua program */
    public function program()
    {
        $p        = Pengaturan::semua();
        $programs = ProgramKerja::where('aktif', true)->orderBy('urutan')->get();

        return view('landing.program', compact('p', 'programs'));
    }

    /** Halaman Acara: semua acara (bisa difilter di halaman) */
    public function acaraList()
    {
        $acaras = Acara::where('aktif', true)->orderByDesc('tanggal')->get();

        return view('landing.acara-list', compact('acaras'));
    }

    /** Halaman Kontak */
    public function kontak()
    {
        $p = Pengaturan::semua();

        return view('landing.kontak', compact('p'));
    }

    /** Detail satu acara (artikel) */
    public function acara($id)
    {
        $acara = Acara::where('aktif', true)->findOrFail($id);

        $lainnya = Acara::where('aktif', true)
            ->where('id', '!=', $acara->id)
            ->orderByDesc('tanggal')
            ->take(3)
            ->get();

        return view('landing.acara', compact('acara', 'lainnya'));
    }

    /** Pecah teks misi (satu baris = satu poin) jadi collection */
    private function pecahMisi($p)
    {
        return collect(preg_split('/\r\n|\r|\n/', $p['misi'] ?? ''))
            ->map(fn ($m) => trim($m))
            ->filter()
            ->values();
    }
}
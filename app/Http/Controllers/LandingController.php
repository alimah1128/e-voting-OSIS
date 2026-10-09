<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Pengaturan;
use App\Models\ProgramKerja;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        $p = Pengaturan::semua();

        $programs = ProgramKerja::where('aktif', true)->orderBy('urutan')->get();
        $acaras   = Acara::where('aktif', true)->orderByDesc('tanggal')->take(6)->get();
        $jumlahAcara = Acara::where('aktif', true)->count();

        $jumlahSiswa = DB::table('siswas')->count();

        $misi = collect(preg_split('/\r\n|\r|\n/', $p['misi'] ?? ''))
            ->map(fn ($m) => trim($m))->filter()->values();

        return view('landing.index', compact(
            'p', 'programs', 'acaras', 'jumlahAcara', 'jumlahSiswa', 'misi'
        ));
    }

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
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    private array $kunci = [
        'hero_judul', 'hero_tagline', 'tentang_profil', 'visi', 'misi',
        'kontak_alamat', 'kontak_email', 'kontak_instagram',
    ];

    private array $kunciFoto = ['hero_foto', 'tentang_foto'];

    public function index()
    {
        $data = Pengaturan::semua();
        return view('admin.pengaturan.index', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_judul'       => 'nullable|string|max:150',
            'hero_tagline'     => 'nullable|string|max:200',
            'tentang_profil'   => 'nullable|string',
            'visi'             => 'nullable|string',
            'misi'             => 'nullable|string',
            'kontak_alamat'    => 'nullable|string|max:255',
            'kontak_email'     => 'nullable|email|max:150',
            'kontak_instagram' => 'nullable|string|max:100',
            'hero_foto'        => 'nullable|image|max:4096',
            'tentang_foto'     => 'nullable|image|max:4096',
        ]);

        foreach ($this->kunci as $k) {
            Pengaturan::updateOrCreate(['kunci' => $k], ['nilai' => $request->input($k)]);
        }

        foreach ($this->kunciFoto as $f) {
            if ($request->hasFile($f)) {
                $lama = Pengaturan::where('kunci', $f)->value('nilai');
                if ($lama) {
                    Storage::disk('public')->delete($lama);
                }
                $path = $request->file($f)->store('pengaturan', 'public');
                Pengaturan::updateOrCreate(['kunci' => $f], ['nilai' => $path]);
            }
        }

        return back()->with('success', 'Pengaturan beranda berhasil disimpan.');
    }
}
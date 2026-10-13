<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Pengaturan konten website, dibagi 3 halaman admin:
 *  - Beranda  : hero (badge, judul, deskripsi, tombol, foto latar)
 *  - Tentang  : foto, profil, visi, misi
 *  - Kontak   : alamat, email, instagram, whatsapp
 *
 * Tiap halaman HANYA menyimpan kunci miliknya sendiri,
 * jadi menyimpan Kontak tidak akan menimpa isi Beranda.
 */
class PengaturanController extends Controller
{
    /* ===================== BERANDA ===================== */

    public function beranda()
    {
        $data = Pengaturan::semua();
        return view('admin.beranda', compact('data'));
    }

    public function updateBeranda(Request $request)
    {
        $request->validate([
            'hero_badge'     => 'nullable|string|max:80',
            'hero_judul'     => 'nullable|string|max:80',
            'hero_judul2'    => 'nullable|string|max:80',
            'hero_tagline'   => 'nullable|string|max:300',
            'hero_btn1_teks' => 'nullable|string|max:30',
            'hero_btn2_teks' => 'nullable|string|max:30',
            'hero_foto'      => 'nullable|image|max:4096',
        ]);

        foreach (['hero_badge', 'hero_judul', 'hero_judul2', 'hero_tagline', 'hero_btn1_teks', 'hero_btn2_teks'] as $kunci) {
            $this->simpan($kunci, $request->input($kunci) ?? '');
        }
        $this->urusFoto($request, 'hero_foto', 'hapus_hero_foto');

        return redirect()->route('admin.beranda')->with('success', 'Beranda diperbarui.');
    }

    /* ===================== TENTANG ===================== */

    public function tentang()
    {
        $data = Pengaturan::semua();
        return view('admin.tentang', compact('data'));
    }

    public function updateTentang(Request $request)
    {
        $request->validate([
            'tentang_profil' => 'nullable|string',
            'visi'           => 'nullable|string',
            'misi'           => 'nullable|string',
            'tentang_foto'   => 'nullable|image|max:4096',
        ]);

        foreach (['tentang_profil', 'visi', 'misi'] as $kunci) {
            $this->simpan($kunci, $request->input($kunci) ?? '');
        }
        $this->urusFoto($request, 'tentang_foto', 'hapus_tentang_foto');

        return redirect()->route('admin.tentang')->with('success', 'Tentang OSIS diperbarui.');
    }

    /* ===================== KONTAK ===================== */

    public function kontak()
    {
        $data = Pengaturan::semua();
        return view('admin.kontak', compact('data'));
    }

    public function updateKontak(Request $request)
    {
        $request->validate([
            'kontak_alamat'    => 'nullable|string|max:255',
            'kontak_email'     => 'nullable|email|max:150',
            'kontak_instagram' => 'nullable|string|max:150',
            'kontak_whatsapp'  => 'nullable|string|max:30',
        ]);

        foreach (['kontak_alamat', 'kontak_email', 'kontak_instagram', 'kontak_whatsapp'] as $kunci) {
            $this->simpan($kunci, $request->input($kunci) ?? '');
        }

        return redirect()->route('admin.kontak')->with('success', 'Kontak diperbarui.');
    }

    /* ===================== HELPER ===================== */

    private function simpan(string $kunci, string $nilai): void
    {
        Pengaturan::updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
    }

    /** Ganti foto kalau ada file baru, hapus foto kalau tombol Hapus dipilih. */
    private function urusFoto(Request $request, string $kunci, string $namaHapus): void
    {
        $lama = Pengaturan::where('kunci', $kunci)->value('nilai');

        if ($request->hasFile($kunci)) {
            if ($lama) Storage::disk('public')->delete($lama);
            $this->simpan($kunci, $request->file($kunci)->store('pengaturan', 'public'));
        } elseif ($request->boolean($namaHapus)) {
            if ($lama) Storage::disk('public')->delete($lama);
            $this->simpan($kunci, '');
        }
    }
}
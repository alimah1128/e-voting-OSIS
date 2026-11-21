<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BlobStorage;
use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index()
    {
        $items = Galeri::orderByDesc('id')->get();
        return view('admin.galeri.index', compact('items'));
    }

    /** Upload banyak foto sekaligus; keterangan (opsional) dipakai untuk semua foto yang diunggah */
    public function store(Request $request)
    {
        $request->validate([
            'foto'       => 'required|array|max:20',
            'foto.*'     => 'image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'keterangan' => 'nullable|string|max:150',
        ], [
            'foto.required' => 'Pilih minimal satu foto.',
            'foto.*.image'  => 'Berkas harus berupa gambar.',
            'foto.*.max'    => 'Ukuran tiap foto maksimal 4 MB.',
        ]);

        foreach ($request->file('foto') as $file) {
            Galeri::create([
                'foto'       => app(BlobStorage::class)->upload($file, 'galeri'),
                'keterangan' => $request->input('keterangan'),
            ]);
        }

        $n = count($request->file('foto'));
        return redirect()->route('admin.galeri.index')->with('success', $n . ' foto ditambahkan.');
    }

    /** Edit foto dan keterangannya */
    public function update(Request $request, $id)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'keterangan' => 'nullable|string|max:150',
        ], [
            'foto.image' => 'Berkas harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 4 MB.',
        ]);

        $item = Galeri::findOrFail($id);
        $fotoLama = null;
        if ($request->hasFile('foto')) {
            $fotoLama = $item->foto;
            $item->foto = app(BlobStorage::class)->upload($request->file('foto'), 'galeri');
        }
        $item->keterangan = $request->input('keterangan');
        $item->save();
        app(BlobStorage::class)->hapus($fotoLama);

        return redirect()->route('admin.galeri.index')->with('success', 'Foto dan keterangan diperbarui.');
    }

    public function destroy($id)
    {
        $item = Galeri::find($id);

        // Foto sudah tidak ada (mis. terhapus di tab lain / klik dua kali): jangan 404, balik saja ke galeri
        if (!$item) {
            return redirect()->route('admin.galeri.index')->with('success', 'Foto sudah tidak ada di galeri.');
        }

        if ($item->foto) app(BlobStorage::class)->hapus($item->foto);
        $item->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'foto.*'     => 'image|max:4096',
            'keterangan' => 'nullable|string|max:150',
        ], [
            'foto.required' => 'Pilih minimal satu foto.',
            'foto.*.image'  => 'Berkas harus berupa gambar.',
            'foto.*.max'    => 'Ukuran tiap foto maksimal 4 MB.',
        ]);

        foreach ($request->file('foto') as $file) {
            Galeri::create([
                'foto'       => $file->store('galeri', 'public'),
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
            'foto' => 'nullable|image|max:4096',
            'keterangan' => 'nullable|string|max:150',
        ], [
            'foto.image' => 'Berkas harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 4 MB.',
        ]);

        $item = Galeri::findOrFail($id);
        if ($request->hasFile('foto')) {
            $fotoLama = $item->foto;
            $item->foto = $request->file('foto')->store('galeri', 'public');
            if ($fotoLama) Storage::disk('public')->delete($fotoLama);
        }
        $item->keterangan = $request->input('keterangan');
        $item->save();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto dan keterangan diperbarui.');
    }

    public function destroy($id)
    {
        $item = Galeri::findOrFail($id);
        if ($item->foto) Storage::disk('public')->delete($item->foto);
        $item->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BlobStorage;
use App\Models\Acara;
use Illuminate\Http\Request;

class AcaraController extends Controller
{
    /** Daftar di kiri + form tambah di kanan */
    public function index()
    {
        $items = Acara::orderByDesc('tanggal')->get();
        return view('admin.acara.index', ['items' => $items, 'item' => null]);
    }

    /** Form tambah ada di halaman daftar */
    public function create()
    {
        return redirect()->route('admin.acara.index');
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        if ($request->hasFile('foto')) {
            $data['foto'] = app(BlobStorage::class)->upload($request->file('foto'), 'acara');
        }
        Acara::create($data);

        return redirect()->route('admin.acara.index')->with('success', 'Acara ditambahkan.');
    }

    /** Daftar di kiri + form edit (terisi) di kanan */
    public function edit($id)
    {
        $items = Acara::orderByDesc('tanggal')->get();
        $item  = Acara::findOrFail($id);
        return view('admin.acara.index', compact('items', 'item'));
    }

    public function update(Request $request, $id)
    {
        $item = Acara::findOrFail($id);
        $data = $this->validasi($request);

        $lama = $item->foto;
        if ($request->hasFile('foto')) {
            $data['foto'] = app(BlobStorage::class)->upload($request->file('foto'), 'acara');
        }
        $item->update($data);
        if (isset($data['foto'])) app(BlobStorage::class)->hapus($lama);

        return redirect()->route('admin.acara.index')->with('success', 'Acara diperbarui.');
    }

    public function destroy($id)
    {
        $item = Acara::findOrFail($id);
        if ($item->foto) app(BlobStorage::class)->hapus($item->foto);
        $item->delete();

        return redirect()->route('admin.acara.index')->with('success', 'Acara dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'tanggal'   => 'required|date',
            'lokasi'    => 'nullable|string|max:150',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);
        $data['aktif'] = $request->boolean('aktif');
        unset($data['foto']);

        return $data;
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $data['foto'] = $request->file('foto')->store('acara', 'public');
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

        if ($request->hasFile('foto')) {
            if ($item->foto) Storage::disk('public')->delete($item->foto);
            $data['foto'] = $request->file('foto')->store('acara', 'public');
        }
        $item->update($data);

        return redirect()->route('admin.acara.index')->with('success', 'Acara diperbarui.');
    }

    public function destroy($id)
    {
        $item = Acara::findOrFail($id);
        if ($item->foto) Storage::disk('public')->delete($item->foto);
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
            'foto'      => 'nullable|image|max:2048',
        ]);
        $data['aktif'] = $request->boolean('aktif');
        unset($data['foto']);

        return $data;
    }
}
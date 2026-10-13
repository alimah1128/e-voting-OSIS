<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramKerjaController extends Controller
{
    /** Daftar di kiri + form tambah di kanan */
    public function index()
    {
        $items = ProgramKerja::orderBy('urutan')->get();
        return view('admin.program_kerja.index', ['items' => $items, 'item' => null]);
    }

    /** Form tambah ada di halaman daftar */
    public function create()
    {
        return redirect()->route('admin.program-kerja.index');
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('program-kerja', 'public');
        }
        ProgramKerja::create($data);

        return redirect()->route('admin.program-kerja.index')->with('success', 'Program kerja ditambahkan.');
    }

    /** Daftar di kiri + form edit (terisi) di kanan */
    public function edit($id)
    {
        $items = ProgramKerja::orderBy('urutan')->get();
        $item  = ProgramKerja::findOrFail($id);
        return view('admin.program_kerja.index', compact('items', 'item'));
    }

    public function update(Request $request, $id)
    {
        $item = ProgramKerja::findOrFail($id);
        $data = $this->validasi($request);

        if ($request->hasFile('foto')) {
            if ($item->foto) Storage::disk('public')->delete($item->foto);
            $data['foto'] = $request->file('foto')->store('program-kerja', 'public');
        }
        $item->update($data);

        return redirect()->route('admin.program-kerja.index')->with('success', 'Program kerja diperbarui.');
    }

    public function destroy($id)
    {
        $item = ProgramKerja::findOrFail($id);
        if ($item->foto) Storage::disk('public')->delete($item->foto);
        $item->delete();

        return redirect()->route('admin.program-kerja.index')->with('success', 'Program kerja dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'ikon'      => 'nullable|string|max:10',
            'urutan'    => 'nullable|integer|min:0',
            'foto'      => 'nullable|image|max:2048',
        ]);
        $data['aktif']  = $request->boolean('aktif');
        $data['urutan'] = $data['urutan'] ?? 0;
        unset($data['foto']);

        return $data;
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AcaraController extends Controller
{
    public function index()
    {
        $items = Acara::orderByDesc('tanggal')->get();
        return view('admin.acara.index', compact('items'));
    }

    public function create()
    {
        return view('admin.acara.form', ['item' => null]);
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

    public function edit($id)
    {
        $item = Acara::findOrFail($id);
        return view('admin.acara.form', compact('item'));
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

        return back()->with('success', 'Acara dihapus.');
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
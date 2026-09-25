<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Models\PeriodePemilihan;
use Illuminate\Http\Request;

class KandidatController extends Controller
{
    public function index()
    {
        $periodeAktif = PeriodePemilihan::where('status', 'active')->first();

        $kandidats = $periodeAktif
            ? Kandidat::where('periode_id', $periodeAktif->id)->orderBy('no_urut')->get()
            : collect();

        return view('admin.kandidat.index', compact('kandidats', 'periodeAktif'));
    }

    public function store(Request $request)
    {
        $periodeAktif = PeriodePemilihan::where('status', 'active')->first();

        if (! $periodeAktif) {
            return redirect()->route('admin.kandidat.index')
                ->with('error', 'Tidak ada periode yang sedang aktif. Aktifkan periode dulu di menu Kelola Periode.');
        }

        $data = $this->validasi($request);
        $data['periode_id'] = $periodeAktif->id;
        $data['foto'] = $this->simpanFoto($request);

        Kandidat::create($data);

        return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $kandidat = Kandidat::findOrFail($id);
        $data = $this->validasi($request);

        if ($request->hasFile('foto')) {
            // Hapus foto lama kalau ada, lalu simpan yang baru
            if ($kandidat->foto && file_exists(public_path('image/' . $kandidat->foto))) {
                unlink(public_path('image/' . $kandidat->foto));
            }
            $data['foto'] = $this->simpanFoto($request);
        }

        $kandidat->update($data);

        return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kandidat = Kandidat::findOrFail($id);

        if ($kandidat->foto && file_exists(public_path('image/' . $kandidat->foto))) {
            unlink(public_path('image/' . $kandidat->foto));
        }

        $kandidat->delete();

        return redirect()->route('admin.kandidat.index')->with('success', 'Kandidat berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $validated = $request->validate([
            'no_urut' => 'required|integer',
            'nama_ketua' => 'required|string|max:255',
            'kelas_ketua' => 'required|string|max:255',
            'nama_wakil' => 'required|string|max:255',
            'kelas_wakil' => 'required|string|max:255',
            'visi' => 'required|string',
            'misi' => 'nullable|array',
            'misi.*' => 'nullable|string',
            'program_kerja' => 'nullable|array',
            'program_kerja.*' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Buang poin misi/program kerja yang kosong
        $validated['misi'] = array_values(array_filter($validated['misi'] ?? [], fn ($v) => trim($v) !== ''));
        $validated['program_kerja'] = array_values(array_filter($validated['program_kerja'] ?? [], fn ($v) => trim($v) !== ''));

        unset($validated['foto']);

        return $validated;
    }

    private function simpanFoto(Request $request): ?string
    {
        if (! $request->hasFile('foto')) {
            return null;
        }

        $file = $request->file('foto');
        $filename = 'kandidat_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('image'), $filename);

        return $filename;
    }
}
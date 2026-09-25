<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodePemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = PeriodePemilihan::orderByDesc('tanggal_mulai')->get();
        return view('admin.periode.index', compact('periodes'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['status'] = 'draft';

        PeriodePemilihan::create($data);

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $periode = PeriodePemilihan::findOrFail($id);
        $data = $this->validasi($request);

        $periode->update($data);

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    /**
     * Ubah status periode (Draft/Active/Inactive).
     * Kalau di-set jadi Active, semua periode lain otomatis dijadikan Inactive
     * (aturan bisnis: cuma boleh ada 1 periode aktif dalam satu waktu).
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:draft,active,inactive',
        ]);

        $periode = PeriodePemilihan::findOrFail($id);

        DB::transaction(function () use ($request, $periode) {
            if ($request->status === 'active') {
                PeriodePemilihan::where('id', '!=', $periode->id)
                    ->where('status', 'active')
                    ->update(['status' => 'inactive']);
            }

            $periode->update(['status' => $request->status]);
        });

        return redirect()->route('admin.periode.index')->with('success', 'Status periode berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $periode = PeriodePemilihan::findOrFail($id);
        $periode->delete(); // kandidat & votes terkait ikut terhapus (foreign key cascadeOnDelete)

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);
    }
}
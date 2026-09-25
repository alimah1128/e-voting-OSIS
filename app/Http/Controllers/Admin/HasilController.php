<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Models\PeriodePemilihan;
use App\Exports\HasilVotingExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class HasilController extends Controller
{
    public function index(Request $request)
    {
        $periodes = PeriodePemilihan::orderByDesc('tanggal_mulai')->get();

        if ($periodes->isEmpty()) {
            return view('admin.hasil.index', [
                'periodes' => $periodes,
                'periodeTerpilih' => null,
            ]);
        }

        $periodeId = $request->query('periode_id')
            ?? optional($periodes->firstWhere('status', 'active'))->id
            ?? $periodes->first()->id;

        $data = $this->hitungData($periodeId);

        return view('admin.hasil.index', array_merge(
            ['periodes' => $periodes],
            $data
        ));
    }

    public function resetVote(string $periodeId)
    {
        DB::table('votes')->where('periode_id', $periodeId)->delete();

        return redirect()->route('admin.hasil.index', ['periode_id' => $periodeId])
            ->with('success', 'Data vote untuk periode ini berhasil direset.');
    }

    public function exportExcel(string $periodeId)
    {
        $data = $this->hitungData($periodeId);

        return Excel::download(
            new HasilVotingExport($data),
            $this->namaFileAman($data['periodeTerpilih']->nama_periode) . '.xlsx'
        );
    }

    public function exportPdf(string $periodeId)
    {
        $data = $this->hitungData($periodeId);

        $data['chartUrl'] = $this->buatGambarChartBase64($data['hasilPerKandidat']);

        $pdf = Pdf::loadView('admin.hasil.pdf', $data);

        return $pdf->download(
            $this->namaFileAman($data['periodeTerpilih']->nama_periode) . '.pdf'
        );
    }

    private function buatGambarChartBase64($hasilPerKandidat): string
    {
        $lebar = 500;
        $tinggi = 300;
        $img = imagecreatetruecolor($lebar, $tinggi);

        $putih = imagecolorallocate($img, 255, 255, 255);
        $abuGaris = imagecolorallocate($img, 226, 232, 240);
        $abuTeks = imagecolorallocate($img, 51, 65, 85);
        $warnaBar = [
            imagecolorallocate($img, 45, 107, 160),
            imagecolorallocate($img, 56, 189, 248),
            imagecolorallocate($img, 74, 222, 128),
            imagecolorallocate($img, 192, 132, 252),
            imagecolorallocate($img, 245, 158, 11),
            imagecolorallocate($img, 244, 114, 182),
        ];

        imagefill($img, 0, 0, $putih);

        $marginKiri = 50;
        $marginBawah = 50;
        $marginAtas = 20;
        $tinggiArea = $tinggi - $marginBawah - $marginAtas;
        $lebarArea = $lebar - $marginKiri - 20;

        $data = $hasilPerKandidat->values();
        $jumlahBar = $data->count();
        $suaraMax = $data->max('suara') ?: 1;

        // sumbu Y (garis bantu horizontal)
        for ($i = 0; $i <= 4; $i++) {
            $y = $marginAtas + ($tinggiArea / 4) * $i;
            imageline($img, $marginKiri, $y, $lebar - 20, $y, $abuGaris);
            $nilai = round($suaraMax - ($suaraMax / 4) * $i);
            imagestring($img, 2, 15, $y - 6, (string) $nilai, $abuTeks);
        }

        // bar
        $lebarSlot = $lebarArea / max($jumlahBar, 1);
        $lebarBar = $lebarSlot * 0.5;

        foreach ($data as $i => $item) {
            $tinggiBar = ($item->suara / $suaraMax) * $tinggiArea;
            $x1 = $marginKiri + $i * $lebarSlot + ($lebarSlot - $lebarBar) / 2;
            $x2 = $x1 + $lebarBar;
            $y2 = $marginAtas + $tinggiArea;
            $y1 = $y2 - $tinggiBar;

            $warna = $warnaBar[$i % count($warnaBar)];
            imagefilledrectangle($img, (int) $x1, (int) $y1, (int) $x2, (int) $y2, $warna);

            // label no urut di bawah bar
            $label = 'No.' . $item->kandidat->no_urut;
            imagestring($img, 2, (int) $x1, $y2 + 8, $label, $abuTeks);

            // angka suara di atas bar
            imagestring($img, 2, (int) $x1, (int) $y1 - 16, (string) $item->suara, $abuTeks);
        }

        ob_start();
        imagepng($img);
        $isi = ob_get_clean();
        imagedestroy($img);

        return 'data:image/png;base64,' . base64_encode($isi);
    }

    private function namaFileAman(string $namaPeriode): string
    {
        // Ganti spasi jadi underscore, lalu buang semua karakter selain huruf, angka, underscore, dan strip
        $bersih = str_replace(' ', '_', $namaPeriode);
        $bersih = preg_replace('/[^A-Za-z0-9_\-]/', '', $bersih);

        return 'hasil_voting_' . $bersih;
    }

    // ============================================================
    // Statistik kelas dengan siswa belum memilih, diurutkan dari
    // PERSENTASE belum memilih tertinggi (lalu jumlah terbanyak).
    // Kalau ada query ?kelas=..., sekalian ambil daftar NAMA siswa yang
    // belum voting di kelas itu (drill-down waktu nama kelas diklik).
    // ============================================================
    public function belumMemilih(Request $request)
    {
        $periodes = PeriodePemilihan::orderByDesc('tanggal_mulai')->get();

        if ($periodes->isEmpty()) {
            return view('admin.hasil.belum-memilih', [
                'periodes' => $periodes,
                'periodeTerpilih' => null,
                'dataKelas' => collect(),
                'kelasTerpilih' => null,
                'siswaBelum' => collect(),
            ]);
        }

        $periodeId = $request->query('periode_id')
            ?? optional($periodes->firstWhere('status', 'active'))->id
            ?? $periodes->first()->id;

        $periodeTerpilih = PeriodePemilihan::findOrFail($periodeId);

        $sudahMemilihIds = DB::table('votes')
            ->where('periode_id', $periodeId)
            ->pluck('siswa_id');

        $totalPerKelas = DB::table('siswas')
            ->select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        $belumPerKelas = DB::table('siswas')
            ->whereNotIn('id', $sudahMemilihIds)
            ->select('kelas', DB::raw('count(*) as jumlah'))
            ->groupBy('kelas')
            ->pluck('jumlah', 'kelas');

        // Kelas dianggap "perlu perhatian" kalau >= 75% siswanya belum memilih
        $ambang = 75;

        $dataKelas = $totalPerKelas->map(function ($total, $kelas) use ($belumPerKelas, $ambang) {
                $jumlah = $belumPerKelas->get($kelas, 0);
                $persen = $total > 0 ? round($jumlah / $total * 100) : 0;

                return (object) [
                    'kelas' => $kelas,
                    'total' => $total,
                    'jumlah' => $jumlah,
                    'persen' => $persen,
                    'perlu_perhatian' => $persen >= $ambang,
                ];
            })
            ->filter(fn ($row) => $row->jumlah > 0)
            ->sortBy([
                ['persen', 'desc'],   // persentase belum memilih tertinggi dulu
                ['jumlah', 'desc'],   // kalau sama, jumlah siswa terbanyak dulu
            ])
            ->values();

        // Drill-down: kalau ada kelas yang dipilih (dari klik nama kelas di tabel),
        // ambil daftar siswa yang belum voting persis di kelas itu.
        $kelasTerpilih = $request->query('kelas');
        $siswaBelum = collect();

        if ($kelasTerpilih) {
            $siswaBelum = DB::table('siswas')
                ->where('kelas', $kelasTerpilih)
                ->whereNotIn('id', $sudahMemilihIds)
                ->orderBy('nama')
                ->get(['id', 'nis', 'nama', 'username']);
        }

        return view('admin.hasil.belum-memilih', compact(
            'periodes', 'periodeTerpilih', 'dataKelas', 'kelasTerpilih', 'siswaBelum'
        ));
    }

    private function hitungData($periodeId): array
    {
        $periodeTerpilih = PeriodePemilihan::findOrFail($periodeId);

        $totalSiswa = DB::table('siswas')->count();
        $totalSuara = DB::table('votes')->where('periode_id', $periodeId)->count();
        $sudahVoting = DB::table('votes')->where('periode_id', $periodeId)->distinct('siswa_id')->count('siswa_id');
        $belumVoting = max(0, $totalSiswa - $sudahVoting);
        $partisipasi = $totalSiswa > 0 ? round($sudahVoting / $totalSiswa * 100) : 0;

        $hasilPerKandidat = Kandidat::where('periode_id', $periodeId)
            ->orderBy('no_urut')
            ->get()
            ->map(function ($kandidat) use ($periodeId, $totalSuara) {
                $suara = DB::table('votes')
                    ->where('periode_id', $periodeId)
                    ->where('kandidat_id', $kandidat->id)
                    ->count();

                return (object) [
                    'kandidat' => $kandidat,
                    'suara' => $suara,
                    'persen' => $totalSuara > 0 ? round($suara / $totalSuara * 100) : 0,
                ];
            })
            ->sortByDesc('suara')
            ->values();

        return compact('periodeTerpilih', 'totalSiswa', 'totalSuara', 'sudahVoting', 'belumVoting', 'partisipasi', 'hasilPerKandidat');
    }
}
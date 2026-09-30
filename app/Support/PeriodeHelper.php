<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeriodeHelper
{
    /**
     * Periode yang statusnya Active tapi tanggal selesainya sudah lewat
     * otomatis diubah jadi Inactive.
     */
    public static function sinkronkanStatus(): void
    {
        DB::table('periode_pemilihan')
            ->where('status', 'active')
            ->whereDate('tanggal_selesai', '<', Carbon::today()->toDateString())
            ->update(['status' => 'inactive']);
    }

    /**
     * Ambil periode Active (setelah status disinkronkan).
     */
    public static function aktif()
    {
        self::sinkronkanStatus();

        return DB::table('periode_pemilihan')->where('status', 'active')->first();
    }

    /**
     * Periode terakhir (dipakai untuk tampilan tahun ajaran saat tidak ada periode aktif).
     */
    public static function terbaru()
    {
        return DB::table('periode_pemilihan')->orderByDesc('tanggal_mulai')->first();
    }

    /**
     * Posisi waktu sebuah periode: 'belum_mulai', 'berlangsung', atau 'berakhir'.
     * Tanggal selesai dihitung sampai akhir hari (23:59:59).
     */
    public static function statusWaktu($periode): string
    {
        $now = Carbon::now();
        $mulai = Carbon::parse($periode->tanggal_mulai)->startOfDay();
        $selesai = Carbon::parse($periode->tanggal_selesai)->endOfDay();

        if ($now->lessThan($mulai)) {
            return 'belum_mulai';
        }
        if ($now->greaterThan($selesai)) {
            return 'berakhir';
        }
        return 'berlangsung';
    }

    /**
     * Apakah voting untuk periode ini boleh dilakukan sekarang?
     * Syarat: status Active DAN waktu sekarang di antara tanggal mulai dan selesai.
     */
    public static function bisaVoting($periodeId): bool
    {
        self::sinkronkanStatus();

        $periode = DB::table('periode_pemilihan')->where('id', $periodeId)->first();

        if (! $periode || $periode->status !== 'active') {
            return false;
        }

        return self::statusWaktu($periode) === 'berlangsung';
    }
}
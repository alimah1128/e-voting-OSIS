<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu siswa hanya boleh punya satu suara per periode (dijaga oleh database).
     * Kalau tabel votes sudah punya data ganda (siswa_id + periode_id sama),
     * hapus data gandanya dulu sebelum migrate, kalau tidak akan error.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->unique(['periode_id', 'siswa_id'], 'votes_periode_siswa_unique');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique('votes_periode_siswa_unique');
        });
    }
};
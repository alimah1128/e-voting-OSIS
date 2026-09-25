<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTahunAjaranToPeriodePemilihanTable extends Migration
{
    public function up(): void
    {
        Schema::table('periode_pemilihan', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable()->after('nama_periode');
        });
    }

    public function down(): void
    {
        Schema::table('periode_pemilihan', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran');
        });
    }
}
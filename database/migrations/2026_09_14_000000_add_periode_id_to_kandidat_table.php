<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kandidat', function (Blueprint $table) {
            $table->foreignId('periode_id')
                ->nullable()
                ->after('id')
                ->constrained('periode_pemilihan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kandidat', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
};
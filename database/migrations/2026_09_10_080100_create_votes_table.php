<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_pemilihan')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('kandidat_id')->constrained('kandidat')->cascadeOnDelete();
            $table->timestamp('waktu_vote')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
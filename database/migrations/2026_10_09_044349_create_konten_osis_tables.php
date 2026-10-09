<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $t) {
            $t->id();
            $t->string('kunci')->unique();
            $t->text('nilai')->nullable();
            $t->timestamps();
        });

        Schema::create('program_kerja', function (Blueprint $t) {
            $t->id();
            $t->string('judul');
            $t->text('deskripsi')->nullable();
            $t->string('ikon', 10)->nullable();
            $t->string('foto')->nullable();
            $t->unsignedInteger('urutan')->default(0);
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });

        Schema::create('acara', function (Blueprint $t) {
            $t->id();
            $t->string('judul');
            $t->text('deskripsi')->nullable();
            $t->date('tanggal');
            $t->string('lokasi')->nullable();
            $t->string('foto')->nullable();
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acara');
        Schema::dropIfExists('program_kerja');
        Schema::dropIfExists('pengaturan');
    }
};
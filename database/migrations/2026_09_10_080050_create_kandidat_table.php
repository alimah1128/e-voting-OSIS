<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kandidat', function (Blueprint $table) {
            $table->id();
            $table->integer('no_urut');
            $table->string('nama_ketua');
            $table->string('kelas_ketua');
            $table->string('nama_wakil');
            $table->string('kelas_wakil');
            $table->text('visi');
            $table->json('misi'); // list poin misi, disimpan sebagai array
            $table->json('program_kerja'); // list poin program kerja, disimpan sebagai array
            $table->string('foto')->nullable(); // nama file foto pasangan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kandidat');
    }
};
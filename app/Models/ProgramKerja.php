<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    protected $table = 'program_kerja';
    protected $fillable = ['judul', 'deskripsi', 'ikon', 'foto', 'urutan', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];
}
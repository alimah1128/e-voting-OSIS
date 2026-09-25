<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    protected $table = 'kandidat';

    protected $fillable = [
        'periode_id',
        'no_urut',
        'nama_ketua',
        'kelas_ketua',
        'nama_wakil',
        'kelas_wakil',
        'visi',
        'misi',
        'program_kerja',
        'foto',
    ];

    protected $casts = [
        'misi' => 'array',
        'program_kerja' => 'array',
    ];
}
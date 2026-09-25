<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodePemilihan extends Model
{
    protected $table = 'periode_pemilihan';

    protected $fillable = [
        'nama_periode',
        'tahun_ajaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];
}
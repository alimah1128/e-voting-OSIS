<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    protected $table = 'acara';
    protected $fillable = ['judul', 'deskripsi', 'tanggal', 'lokasi', 'foto', 'aktif'];
    protected $casts = ['tanggal' => 'date', 'aktif' => 'boolean'];
}
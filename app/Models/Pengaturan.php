<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    protected $fillable = ['kunci', 'nilai'];

    public static function semua(): array
    {
        return static::pluck('nilai', 'kunci')->toArray();
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswaList = [
            ['nis' => '2024001', 'nama' => 'Ahmad Fauzi',        'kelas' => 'X RPL 1',   'jenis_kelamin' => 'Laki-laki'],
            ['nis' => '2024002', 'nama' => 'Bunga Lestari',       'kelas' => 'X RPL 2',   'jenis_kelamin' => 'Perempuan'],
            ['nis' => '2024003', 'nama' => 'Citra Maharani',      'kelas' => 'XI RPL 1',  'jenis_kelamin' => 'Perempuan'],
            ['nis' => '2024004', 'nama' => 'Dedi Setiawan',       'kelas' => 'XI RPL 2',  'jenis_kelamin' => 'Laki-laki'],
            ['nis' => '2024005', 'nama' => 'Eka Putri Ramadhani', 'kelas' => 'XII RPL 1', 'jenis_kelamin' => 'Perempuan'],
            ['nis' => '2024006', 'nama' => 'Fajar Nugraha',       'kelas' => 'XII RPL 2', 'jenis_kelamin' => 'Laki-laki'],
            ['nis' => '2024007', 'nama' => 'Alimah',              'kelas' => 'XII RPL 3', 'jenis_kelamin' => 'Perempuan'],
        ];

        foreach ($siswaList as $siswa) {
            // Username otomatis dari nama (lowercase, tanpa spasi)
            $username = Str::lower(Str::slug($siswa['nama'], ''));

            DB::table('siswas')->insert([
                'nis' => $siswa['nis'],
                'nama' => $siswa['nama'],
                'kelas' => $siswa['kelas'],
                'jenis_kelamin' => $siswa['jenis_kelamin'],
                'username' => $username,
                // Password = NIS siswa masing-masing (otomatis unique)
                'password' => Hash::make($siswa['nis']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
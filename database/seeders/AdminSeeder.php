<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Kalau akun admin dengan username ini sudah ada, cukup update nama lengkapnya
        // (menghindari error duplicate kalau seeder dijalankan ulang)
        $existing = DB::table('admins')->where('username', 'admin')->first();

        if ($existing) {
            DB::table('admins')->where('username', 'admin')->update([
                'nama_lengkap' => 'Admin OSIS',
                'updated_at' => now(),
            ]);
            return;
        }

        DB::table('admins')->insert([
            'username' => 'admin',
            'nama_lengkap' => 'Admin OSIS',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        // Baris contoh, biar admin tau format pengisiannya
        return [
            ['2024099', 'Contoh Nama Siswa', 'X RPL 1', 'Laki-laki'],
        ];
    }

    public function headings(): array
    {
        return ['NIS', 'Nama', 'Kelas', 'Jenis Kelamin'];
    }
}
<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class SiswaImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * Setiap baris di file Excel/CSV akan masuk ke sini sebagai $row.
     * Kolom wajib: NIS, Nama (atau "Nama Lengkap"), Kelas, Jenis Kelamin.
     */
    public function model(array $row): Model|array|null
    {
        $nis           = $this->ambil($row, ['nis']);
        $nama          = $this->ambil($row, ['nama', 'nama_lengkap', 'nama_siswa']);
        $kelas         = $this->ambil($row, ['kelas']);
        $jenisKelamin  = $this->ambil($row, ['jenis_kelamin', 'jk']);

        if (!$nis || !$nama || !$kelas || !$jenisKelamin) {
            return null;
        }

        return new Siswa([
            'nis'           => $nis,
            'nama'          => $nama,
            'kelas'         => $kelas,
            'jenis_kelamin' => $jenisKelamin,
            // Username = nama + NIS, biar dijamin unik walau ada nama sama (misal 2 "Ahmad")
            'username'      => Str::lower(Str::slug($nama, '')) . $nis,
            // Password otomatis dari NIS
            'password'      => Hash::make($nis),
        ]);
    }

    /**
     * Validasi per baris SEBELUM masuk ke model().
     * Baris yang gagal validasi akan DILEWATI (bukan bikin semua baris batal),
     * berkat SkipsOnFailure + SkipsFailures.
     */
    public function rules(): array
    {
        return [
            'nis' => 'required|unique:siswas,nis',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis.unique' => 'NIS sudah terdaftar sebelumnya.',
            'nis.required' => 'NIS wajib diisi.',
        ];
    }

    private function ambil(array $row, array $kemungkinanKolom)
    {
        foreach ($kemungkinanKolom as $kolom) {
            if (!empty($row[$kolom])) {
                return trim((string) $row[$kolom]);
            }
        }
        return null;
    }
}
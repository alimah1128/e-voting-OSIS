<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\ProfilSiswaController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KandidatController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\HasilController;
use Illuminate\Http\Request;

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect('/login');
});

// Route Autentikasi (Guest) - Siswa
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Khusus Siswa (Diproteksi dengan Session Siswa)
Route::middleware(['cek.siswa'])->group(function () {

    // Halaman Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman Daftar Kandidat
    Route::get('/kandidat', [VotingController::class, 'daftarKandidat'])->name('kandidat');

    // Halaman Konfirmasi Pilihan berdasarkan ID Kandidat
    Route::get('/konfirmasi/{id}', [VotingController::class, 'konfirmasi'])->name('konfirmasi');

    // Proses Submit Pilihan
    Route::post('/submit-vote', [VotingController::class, 'submitVote'])->name('submit.vote');

    // Halaman Profil Siswa (read-only)
    Route::get('/profil', [ProfilSiswaController::class, 'index'])->name('siswa.profil');

    // Halaman Edit Profil Siswa (foto & password) — BARU
    Route::get('/profil/edit', [ProfilSiswaController::class, 'edit'])->name('siswa.profil.edit');
    Route::post('/profil/update', [ProfilSiswaController::class, 'update'])->name('siswa.profil.update');

    // Halaman Selesai / Berhasil Voting
    Route::get('/selesai', [VotingController::class, 'selesai'])->name('selesai');

});

// ================= ADMIN =================

// Route Autentikasi (Guest) - Admin
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Route Khusus Admin (Diproteksi dengan Session Admin)
Route::prefix('admin')->name('admin.')->middleware(['cek.admin'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Data Siswa (CRUD + Hapus Semua + Import)
    Route::delete('/siswa/delete-all', [SiswaController::class, 'deleteAll'])->name('siswa.deleteAll');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::resource('siswa', SiswaController::class);

    // Kelola Data Kandidat (CRUD)
    Route::resource('kandidat', KandidatController::class);

    // Kelola Periode (CRUD + Update Status)
    Route::put('/periode/{id}/status', [PeriodeController::class, 'updateStatus'])->name('periode.updateStatus');
    Route::resource('periode', PeriodeController::class);

    // Profil Admin
    Route::get('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::post('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');

    // Hasil Voting
    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::get('/belum-memilih', [HasilController::class, 'belumMemilih'])->name('belumMemilih');
    Route::post('/hasil/reset/{periodeId}', [HasilController::class, 'resetVote'])->name('hasil.reset');
    Route::get('/hasil/export-excel/{periodeId}', [HasilController::class, 'exportExcel'])->name('hasil.exportExcel');
    Route::get('/hasil/export-pdf/{periodeId}', [HasilController::class, 'exportPdf'])->name('hasil.exportPdf');

});
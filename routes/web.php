<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\ProfilSiswaController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KandidatController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\HasilController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\ProgramKerjaController;
use App\Http\Controllers\Admin\AcaraController;
use App\Http\Middleware\NoCache;
use Illuminate\Http\Request;

// Halaman utama: Website OSIS (konten dinamis dari database)
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Artikel acara (halaman publik)
Route::get('/acara/{id}', [LandingController::class, 'acara'])->name('acara.show');

// Route Autentikasi (Guest) - Siswa
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Khusus Siswa (Diproteksi dengan Session Siswa)
Route::middleware(['cek.siswa', NoCache::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kandidat', [VotingController::class, 'daftarKandidat'])->name('kandidat');
    Route::get('/konfirmasi/{id}', [VotingController::class, 'konfirmasi'])->name('konfirmasi');
    Route::post('/submit-vote', [VotingController::class, 'submitVote'])->name('submit.vote');

    Route::get('/profil', [ProfilSiswaController::class, 'index'])->name('siswa.profil');
    Route::get('/profil/edit', [ProfilSiswaController::class, 'edit'])->name('siswa.profil.edit');
    Route::post('/profil/update', [ProfilSiswaController::class, 'update'])->name('siswa.profil.update');

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

    // ---------- Beranda Admin (ringkasan konten website) ----------
    Route::get('/beranda', function () {
        return view('admin.beranda', [
            'jumlahProgram' => DB::table('program_kerja')->count(),
            'jumlahAcara'   => DB::table('acara')->count(),
            'acaraAkan'     => DB::table('acara')->whereDate('tanggal', '>=', today())->count(),
        ]);
    })->name('beranda');

    // ---------- KONTEN WEBSITE ----------
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::resource('program-kerja', ProgramKerjaController::class)->except('show');
    Route::resource('acara', AcaraController::class)->except('show');

    // ---------- PEMILIHAN (e-voting) ----------
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::delete('/siswa/delete-all', [SiswaController::class, 'deleteAll'])->name('siswa.deleteAll');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::resource('siswa', SiswaController::class);

    Route::resource('kandidat', KandidatController::class);

    Route::put('/periode/{id}/status', [PeriodeController::class, 'updateStatus'])->name('periode.updateStatus');
    Route::resource('periode', PeriodeController::class);

    Route::get('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::post('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');

    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::get('/belum-memilih', [HasilController::class, 'belumMemilih'])->name('belumMemilih');
    Route::post('/hasil/reset/{periodeId}', [HasilController::class, 'resetVote'])->name('hasil.reset');
    Route::get('/hasil/export-excel/{periodeId}', [HasilController::class, 'exportExcel'])->name('hasil.exportExcel');
    Route::get('/hasil/export-pdf/{periodeId}', [HasilController::class, 'exportPdf'])->name('hasil.exportPdf');

});
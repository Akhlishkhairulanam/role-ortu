<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\NilaiController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaran;
use App\Http\Controllers\Parent\DashboardController as ParentDashboard;
use App\Http\Controllers\Parent\JadwalController as ParentJadwal;
use App\Http\Controllers\Parent\NilaiController as ParentNilai;
use App\Http\Controllers\Parent\PembayaranController as ParentPembayaran;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (TANPA MIDDLEWARE)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// FORM LOGIN
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// PROSES LOGIN
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Manajemen Siswa
        Route::resource('students', StudentController::class);
        Route::post('students/{student}/reset-password', [StudentController::class, 'resetPassword'])->name('students.reset-password');
        Route::post('students/{student}/create-parent-account', [StudentController::class, 'createParentAccount'])->name('students.create-parent');

        // Kelas
        Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class);

        // Mapel
        Route::resource('mata-pelajaran', \App\Http\Controllers\Admin\MataPelajaranController::class);

        // Jadwal
        Route::resource('jadwal', JadwalController::class);
        Route::get('jadwal/kelas/{kelas}', [JadwalController::class, 'byKelas'])->name('jadwal.by-kelas');

        // Nilai
        Route::resource('nilai', NilaiController::class);
        Route::post('nilai/{nilai}/publish', [NilaiController::class, 'publish'])->name('nilai.publish');
        Route::get('nilai/student/{student}', [NilaiController::class, 'byStudent'])->name('nilai.by-student');
        Route::get('nilai/export/pdf', [NilaiController::class, 'exportPdf'])->name('nilai.export.pdf');
        Route::get('nilai/export/excel', [NilaiController::class, 'exportExcel'])->name('nilai.export.excel');

        // Pembayaran
        Route::resource('jenis-pembayaran', \App\Http\Controllers\Admin\JenisPembayaranController::class);
        Route::resource('tagihan', \App\Http\Controllers\Admin\TagihanController::class);

        Route::get('pembayaran', [AdminPembayaran::class, 'index'])->name('pembayaran.index');
        Route::get('pembayaran/{pembayaran}', [AdminPembayaran::class, 'show'])->name('pembayaran.show');
        Route::post('pembayaran/{pembayaran}/verify', [AdminPembayaran::class, 'verify'])->name('pembayaran.verify');
        Route::post('pembayaran/{pembayaran}/reject', [AdminPembayaran::class, 'reject'])->name('pembayaran.reject');
        Route::get('pembayaran/export/pdf', [AdminPembayaran::class, 'exportPdf'])->name('pembayaran.export.pdf');
        Route::get('pembayaran/export/excel', [AdminPembayaran::class, 'exportExcel'])->name('pembayaran.export.excel');

        // Laporan
        Route::get('laporan/nilai', [\App\Http\Controllers\Admin\LaporanController::class, 'nilai'])->name('laporan.nilai');
        Route::get('laporan/pembayaran', [\App\Http\Controllers\Admin\LaporanController::class, 'pembayaran'])->name('laporan.pembayaran');
    });


/*
|--------------------------------------------------------------------------
| PARENT ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:parent', 'parent.access'])
    ->prefix('parent')
    ->name('parent.')
    ->group(function () {

        Route::get('/dashboard', [ParentDashboard::class, 'index'])->name('dashboard');

        // Profil Anak
        Route::get('/profil-anak', [ParentDashboard::class, 'profilAnak'])->name('profil-anak');

        // Jadwal
        Route::get('/jadwal', [ParentJadwal::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/filter', [ParentJadwal::class, 'filter'])->name('jadwal.filter');

        // Nilai
        Route::get('/nilai', [ParentNilai::class, 'index'])->name('nilai.index');
        Route::get('/nilai/filter', [ParentNilai::class, 'filter'])->name('nilai.filter');
        Route::get('/nilai/export-pdf', [ParentNilai::class, 'exportPdf'])->name('nilai.export-pdf');

        // Pembayaran
        Route::get('/pembayaran', [ParentPembayaran::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/{tagihan}', [ParentPembayaran::class, 'show'])->name('pembayaran.show');
        Route::post('/pembayaran/{tagihan}/bayar', [ParentPembayaran::class, 'bayar'])->name('pembayaran.bayar');
        Route::get('/pembayaran/{pembayaran}/invoice', [ParentPembayaran::class, 'invoice'])->name('pembayaran.invoice');
        Route::get('/pembayaran/{pembayaran}/bukti-pdf', [ParentPembayaran::class, 'buktiPdf'])->name('pembayaran.bukti-pdf');
        Route::get('/pembayaran/export/excel', [ParentPembayaran::class, 'exportExcel'])->name('pembayaran.export-excel');

        // Riwayat Pembayaran
        Route::get('/riwayat-pembayaran', [ParentPembayaran::class, 'riwayat'])->name('pembayaran.riwayat');

        // Ganti password
        Route::get('/ganti-password', [ParentDashboard::class, 'gantiPasswordForm'])->name('ganti-password');
        Route::post('/ganti-password', [ParentDashboard::class, 'gantiPassword'])->name('ganti-password.update');
    });

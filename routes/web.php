<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/* ================= ADMIN ================= */
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\NilaiController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaran;

/* ================= PARENT ================= */
use App\Http\Controllers\Parents\DashboardController as ParentDashboard;
use App\Http\Controllers\Parents\JadwalController as ParentJadwal;
use App\Http\Controllers\Parents\NilaiController as ParentNilai;
use App\Http\Controllers\Parents\PembayaranController as ParentPembayaran;

/* ================= STUDENT ================= */
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\JadwalController as StudentJadwal;
use App\Http\Controllers\Student\NilaiController as StudentNilai;
use App\Http\Controllers\Student\PembayaranController as StudentPembayaran;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isParent()) {
            return redirect()->route('parent.dashboard');
        }

        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

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

        Route::resource('students', StudentController::class);
        Route::post('students/{student}/reset-password', [StudentController::class, 'resetPassword'])
            ->name('students.reset-password');
        Route::post('students/{student}/create-parent-account', [StudentController::class, 'createParentAccount'])
            ->name('students.create-parent');

        Route::resource('jadwal', JadwalController::class);
        Route::resource('nilai', NilaiController::class);

        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [AdminPembayaran::class, 'index'])->name('index');
            Route::post('{pembayaran}/verify', [AdminPembayaran::class, 'verify'])->name('verify');
        });
    });

/*
|--------------------------------------------------------------------------
| PARENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'parent.access'])
    ->prefix('parent')
    ->name('Parent.')
    ->group(function () {

        Route::get('/dashboard', [ParentDashboard::class, 'index'])->name('dashboard');
        Route::get('/profil-anak', [ParentDashboard::class, 'profilAnak'])->name('profil-anak');

        Route::get('/jadwal', [ParentJadwal::class, 'index'])->name('jadwal.index');
        Route::get('/nilai', [ParentNilai::class, 'index'])->name('nilai.index');

        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [ParentPembayaran::class, 'index'])->name('index');
            Route::post('{tagihan}/bayar', [ParentPembayaran::class, 'bayar'])->name('bayar');
            Route::get('status', [ParentPembayaran::class, 'status'])->name('status'); // AJAX
        });

        Route::get('/ganti-password', [ParentDashboard::class, 'gantiPasswordForm'])->name('ganti-password');
        Route::post('/ganti-password', [ParentDashboard::class, 'gantiPassword'])->name('ganti-password.update');
    });

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'student.access'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
        Route::get('/profil', [StudentDashboard::class, 'profil'])->name('profil');

        Route::get('/jadwal', [StudentJadwal::class, 'index'])->name('jadwal.index');
        Route::get('/nilai', [StudentNilai::class, 'index'])->name('nilai.index');

        Route::get('/pembayaran', [StudentPembayaran::class, 'index'])->name('pembayaran.index');

        Route::get('/ganti-password', [StudentDashboard::class, 'gantiPasswordForm'])->name('ganti-password');
        Route::post('/ganti-password', [StudentDashboard::class, 'gantiPassword'])->name('ganti-password.update');
    });

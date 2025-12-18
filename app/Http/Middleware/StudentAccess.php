<?php
// app/Http/Middleware/StudentAccess.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Cek role student
        if (!$user->isStudent()) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        // Cek apakah user memiliki relasi student
        if (!$user->student) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun siswa tidak valid!');
        }

        // Untuk route yang membutuhkan data siswa tertentu
        if ($request->has('student_id')) {
            if ($request->student_id != $user->student->id) {
                abort(403, 'Akses ditolak! Anda tidak berhak mengakses data ini.');
            }
        }

        return $next($request);
    }
}

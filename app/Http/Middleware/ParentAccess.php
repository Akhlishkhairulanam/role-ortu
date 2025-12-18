<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class ParentAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Belum login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Pastikan role parent
        if (!$user->isParent()) {
            abort(403, 'Akses khusus orang tua');
        }

        // CEK: apakah ortu punya anak (student)
        $hasStudent = Student::where('parent_user_id', $user->id)->exists();

        if (!$hasStudent) {
            Auth::logout();
            return redirect()
                ->route('login')
                ->withErrors(['Akun orang tua belum terhubung ke data siswa']);
        }

        return $next($request);
    }
}

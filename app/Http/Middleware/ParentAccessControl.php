<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentAccessControl
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Pastikan parent hanya akses data anaknya
        if ($user && $user->role === 'parent') {
            $studentId = $request->route('student_id') ?? $request->input('student_id');

            if ($studentId && $user->student && $user->student->id != $studentId) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        return $next($request);
    }
}

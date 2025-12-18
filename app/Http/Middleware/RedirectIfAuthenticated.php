<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // HANYA redirect jika user mengakses halaman login
        if (Auth::check() && $request->routeIs('login')) {
            $user = Auth::user();

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

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user
        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Username / NIS tidak ditemukan.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'username' => ['Akun Anda tidak aktif. Silakan hubungi admin.'],
            ]);
        }

        // Attempt login
        if (!Auth::attempt(
            [
                'username' => $credentials['username'],
                'password' => $credentials['password'],
            ],
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'password' => ['Password yang Anda masukkan salah.'],
            ]);
        }

        // 🔑 Penting untuk cegah 419
        $request->session()->regenerate();

        // Log aktivitas (opsional, kalau pakai spatie activitylog)
        if (function_exists('activity')) {
            activity()
                ->causedBy(Auth::user())
                ->log('User login');
        }

        return $this->redirectBasedOnRole(Auth::user());
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        if (Auth::check() && function_exists('activity')) {
            activity()
                ->causedBy(Auth::user())
                ->log('User logout');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil logout.');
    }

    /**
     * Redirect sesuai role
     */
    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'parent'  => redirect()->route('parent.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default   => $this->logoutAndBack(),
        };
    }

    /**
     * Jika role tidak valid
     */
    protected function logoutAndBack()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('error', 'Role akun tidak dikenali.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba login dengan username atau NIS
        $user = \App\Models\User::where('username', $credentials['username'])
            ->orWhere('nis', $credentials['username'])
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Username atau NIS tidak ditemukan.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'username' => ['Akun Anda tidak aktif. Hubungi admin sekolah.'],
            ]);
        }

        if (Auth::attempt(['username' => $user->username, 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();

            // Log aktivitas login
            activity()
                ->causedBy(Auth::user())
                ->log('User ' . Auth::user()->name . ' melakukan login');

            return $this->redirectBasedOnRole(Auth::user());
        }

        throw ValidationException::withMessages([
            'password' => ['Password yang Anda masukkan salah.'],
        ]);
    }

    public function logout(Request $request)
    {
        // Log aktivitas logout
        activity()
            ->causedBy(Auth::user())
            ->log('User ' . Auth::user()->name . ' melakukan logout');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isParent()) {
            return redirect()->route('parent.dashboard');
        }

        return redirect('/');
    }
}

<?php

// app/Http/Controllers/Student/DashboardController.php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;

        // ✅ TAGIHAN BELUM LUNAS (AMBIL DARI TAGIHAN)
        $tagihanBelumLunas = $student->tagihan()
            ->where('status', 'belum_lunas')
            ->count();

        // ✅ PEMBAYARAN TERAKHIR (VIA TAGIHAN)
        $pembayaranTerakhir = \App\Models\Pembayaran::whereHas('tagihan', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->latest()->first();

        // Jadwal hari ini
        $hariIni = strtolower(now()->translatedFormat('l'));

        $jadwalHariIni = $student->jadwal()
            ->where('hari', $hariIni)
            ->with('mataPelajaran')
            ->get();

        // Nilai terbaru
        $nilaiTerbaru = $student->nilai()
            ->latest()
            ->take(5)
            ->get();

        return view('student.dashboard', compact(
            'student',
            'tagihanBelumLunas',
            'pembayaranTerakhir',
            'jadwalHariIni',
            'nilaiTerbaru'
        ));
    }

    public function profil()
    {
        $student = Auth::user()->student;
        return view('student.profil', compact('student'));
    }

    public function gantiPasswordForm()
    {
        return view('student.ganti-password');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama salah!');
        }

        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        Auth::logout();
        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login kembali.');
    }
}

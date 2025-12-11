<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            return view('parent.no-student');
        }

        // Data untuk dashboard
        $data = [
            'student' => $student->load('kelas'),
            'tagihan_belum_lunas' => $student->tagihan()
                ->where('status', 'belum_lunas')
                ->count(),
            'total_tagihan' => $student->tagihan()
                ->where('status', 'belum_lunas')
                ->sum('jumlah'),
            'jadwal_hari_ini' => $student->kelas->jadwal()
                ->where('hari', now()->locale('id')->dayName)
                ->where('semester', 'Ganjil') // adjust based on current semester
                ->where('tahun_ajaran', '2024/2025') // adjust
                ->with(['mataPelajaran', 'guru'])
                ->orderBy('jam_mulai')
                ->get(),
            'nilai_terbaru' => $student->nilai()
                ->where('is_published', true)
                ->with('mataPelajaran')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('parent.dashboard', $data);
    }

    public function profilAnak()
    {
        $student = auth()->user()->student->load('kelas');
        return view('parent.profil-anak', compact('student'));
    }

    public function gantiPasswordForm()
    {
        return view('parent.ganti-password');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        activity()
            ->causedBy($user)
            ->log('Mengganti password');

        return back()->with('success', 'Password berhasil diubah.');
    }
}

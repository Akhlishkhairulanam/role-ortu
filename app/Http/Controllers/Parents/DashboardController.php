<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $parent = auth()->user();

        $children = $parent->children()
            ->with([
                'kelas',
                'nilai.mataPelajaran',
                'tagihan',
                'kelas.jadwal.mataPelajaran',
                'kelas.jadwal.guru'
            ])
            ->get();

        if ($children->isEmpty()) {
            return view('Parent.no-student');
        }

        // Gabungan data semua anak
        $tagihan_belum_lunas = $children->sum(
            fn($anak) =>
            $anak->tagihan->where('status', 'belum_lunas')->count()
        );

        $total_tagihan = $children->sum(
            fn($anak) =>
            $anak->tagihan->where('status', 'belum_lunas')->sum('jumlah')
        );

        $jadwal_hari_ini = $children->flatMap(function ($anak) {
            return $anak->kelas?->jadwal
                ->where('hari', now()->locale('id')->dayName) ?? collect();
        });

        $nilai_terbaru = $children->flatMap(function ($anak) {
            return $anak->nilai
                ->where('is_published', true)
                ->sortByDesc('created_at')
                ->take(5);
        });

        return view('Parent.dashboard', compact(
            'children',
            'tagihan_belum_lunas',
            'total_tagihan',
            'jadwal_hari_ini',
            'nilai_terbaru'
        ));
    }

    public function profilAnak()
    {
        $student = auth()->user()->student->load('kelas');
        return view('Parent.profil-anak', compact('student'));
    }

    public function gantiPasswordForm()
    {
        return view('Parent.ganti-password');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        // CEK PASSWORD LAMA
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama salah'
            ]);
        }

        // SIMPAN PASSWORD BARU (HASH!)
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui');
    }
}

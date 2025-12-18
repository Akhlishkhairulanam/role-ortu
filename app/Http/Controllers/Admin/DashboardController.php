<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Kelas;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_siswa' => Student::where('status', 'aktif')->count(),
            'total_kelas' => Kelas::count(),
            'tagihan_belum_lunas' => Tagihan::where('status', 'belum_lunas')->sum('jumlah'),
            'tagihan_lunas' => Tagihan::where('status', 'lunas')->sum('jumlah'),
            'pembayaran_pending' => Pembayaran::where('status_verifikasi', 'pending')->count(),

            // Grafik pembayaran bulanan
            'pembayaran_bulanan' => Pembayaran::selectRaw('MONTH(tanggal_bayar) as bulan, SUM(jumlah_bayar) as total')
                ->whereYear('tanggal_bayar', date('Y'))
                ->where('status_verifikasi', 'verified')
                ->groupBy('bulan')
                ->get(),

            // Siswa terbaru
            'siswa_baru' => Student::with('kelas')->latest()->take(5)->get(),

            // Pembayaran terbaru yang perlu verifikasi
            'pembayaran_pending_list' => Pembayaran::with(['tagihan.student', 'tagihan.jenisPembayaran'])
                ->where('status_verifikasi', 'pending')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}

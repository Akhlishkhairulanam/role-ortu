<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function nilai(Request $request)
    {
        $query = Nilai::with(['student', 'mataPelajaran']);

        if ($request->filled('kelas_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $nilai = $query->get();

        return view('admin.laporan.nilai', compact('nilai'));
    }

    public function pembayaran(Request $request)
    {
        $query = Pembayaran::with(['tagihan.student', 'tagihan.jenisPembayaran']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_bayar', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_bayar', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $pembayaran = $query->get();
        $totalPembayaran = $pembayaran->where('status_verifikasi', 'verified')->sum('jumlah_bayar');

        return view('admin.laporan.pembayaran', compact('pembayaran', 'totalPembayaran'));
    }
}

<?php
// app/Http\Controllers\Student\PembayaranController.php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        // Ambil pembayaran VIA tagihan
        $pembayaran = \App\Models\Pembayaran::whereHas(
            'tagihan',
            fn($q) => $q->where('student_id', $student->id)
        )
            ->with('tagihan.jenisPembayaran')
            ->get();

        // Group by jenis pembayaran
        $pembayaranGrouped = $pembayaran->groupBy(
            fn($item) => $item->tagihan->jenisPembayaran->nama_pembayaran
        );

        // Statistik (BERDASARKAN TAGIHAN)
        $totalTagihan = $student->tagihan()->count();
        $totalLunas = $student->tagihan()->where('status', 'lunas')->count();
        $totalBelumLunas = $student->tagihan()->where('status', 'belum_lunas')->count();

        return view('student.pembayaran.index', compact(
            'pembayaranGrouped',
            'totalTagihan',
            'totalLunas',
            'totalBelumLunas'
        ));
    }
    public function invoice($id)
    {
        $pembayaran = Pembayaran::with(['student', 'tagihan'])->findOrFail($id);

        // Authorization: Cek apakah pembayaran milik siswa yang login
        if ($pembayaran->student_id != Auth::user()->student->id) {
            abort(403, 'Akses ditolak!');
        }

        return view('student.pembayaran.invoice', compact('pembayaran'));
    }
}

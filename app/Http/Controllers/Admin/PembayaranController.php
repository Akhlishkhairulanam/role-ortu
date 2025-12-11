<?php
// app/Http/Controllers/Admin/PembayaranController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['tagihan.student', 'tagihan.jenisPembayaran']);

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $pembayaran = $query->latest()->paginate(20);

        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['tagihan.student', 'tagihan.jenisPembayaran']);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function verify(Pembayaran $pembayaran)
    {
        $pembayaran->update(['status_verifikasi' => 'verified']);

        // Update status tagihan jadi lunas
        $pembayaran->tagihan->update(['status' => 'lunas']);

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $pembayaran->update([
            'status_verifikasi' => 'rejected',
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function exportPdf(Request $request)
    {
        $query = Pembayaran::with(['tagihan.student', 'tagihan.jenisPembayaran']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_bayar', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_bayar', '<=', $request->tanggal_sampai);
        }

        $pembayaran = $query->get();

        $pdf = Pdf::loadView('admin.pembayaran.pdf', compact('pembayaran'));
        return $pdf->download('laporan-pembayaran.pdf');
    }

    public function exportExcel(Request $request)
    {
        // Implementation using Laravel Excel
        return back()->with('info', 'Fitur export Excel dalam pengembangan.');
    }
}

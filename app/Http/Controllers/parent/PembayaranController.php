<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('parent.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $tagihan = $student->tagihan()
            ->with(['jenisPembayaran', 'pembayaran'])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBelumLunas = $tagihan->where('status', 'belum_lunas')->sum('jumlah');
        $totalLunas = $tagihan->where('status', 'lunas')->sum('jumlah');

        return view('parent.pembayaran.index', compact('student', 'tagihan', 'totalBelumLunas', 'totalLunas'));
    }

    public function show(Tagihan $tagihan)
    {
        // Pastikan tagihan milik anak user ini
        if ($tagihan->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        $tagihan->load(['jenisPembayaran', 'pembayaran']);

        return view('parent.pembayaran.show', compact('tagihan'));
    }

    public function bayar(Request $request, Tagihan $tagihan)
    {
        // Pastikan tagihan milik anak user ini
        if ($tagihan->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan sudah lunas.');
        }

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Upload bukti bayar
        $buktiBayar = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiBayar = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
        }

        $pembayaran = Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'jumlah_bayar' => $request->jumlah_bayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_bayar' => now(),
            'bukti_bayar' => $buktiBayar,
            'status_verifikasi' => 'pending',
            'catatan' => $request->catatan,
        ]);

        // Jika jumlah bayar >= jumlah tagihan, update status
        if ($request->jumlah_bayar >= $tagihan->jumlah) {
            $tagihan->update(['status' => 'lunas']);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($pembayaran)
            ->log('Melakukan pembayaran: ' . $tagihan->jenisPembayaran->nama_pembayaran);

        return redirect()->route('parent.pembayaran.index')
            ->with('success', 'Pembayaran berhasil. Menunggu verifikasi admin.');
    }

    public function riwayat()
    {
        $student = auth()->user()->student;

        $pembayaran = Pembayaran::whereHas('tagihan', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })
            ->with(['tagihan.jenisPembayaran'])
            ->latest()
            ->paginate(20);

        return view('parent.pembayaran.riwayat', compact('pembayaran'));
    }

    public function invoice(Pembayaran $pembayaran)
    {
        // Pastikan pembayaran milik anak user ini
        if ($pembayaran->tagihan->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        return view('parent.pembayaran.invoice', compact('pembayaran'));
    }

    public function buktiPdf(Pembayaran $pembayaran)
    {
        // Pastikan pembayaran milik anak user ini
        if ($pembayaran->tagihan->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('parent.pembayaran.bukti-pdf', compact('pembayaran'));

        return $pdf->download('bukti-pembayaran-' . $pembayaran->no_invoice . '.pdf');
    }

    public function exportExcel()
    {
        $student = auth()->user()->student;

        // Implementation menggunakan Laravel Excel
        // return Excel::download(new PembayaranExport($student->id), 'riwayat-pembayaran.xlsx');

        return back()->with('info', 'Fitur export Excel dalam pengembangan.');
    }
}

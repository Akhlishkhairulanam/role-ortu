<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    public function index()
    {
        $parent = auth()->user();

        $tagihan = Tagihan::whereIn(
            'student_id',
            $parent->children->pluck('id')
        )->with(['jenisPembayaran', 'pembayaran'])->get();

        $totalBelumLunas = $tagihan->where('status', 'belum_lunas')->sum('jumlah');
        $totalLunas = $tagihan->where('status', 'lunas')->sum('jumlah');

        return view('Parent.pembayaran.index', compact(
            'tagihan',
            'totalBelumLunas',
            'totalLunas'
        ));
    }

    public function show(Tagihan $tagihan)
    {
        $parent = auth()->user();

        abort_if(
            !$parent->children->pluck('id')->contains($tagihan->student_id),
            403
        );

        $tagihan->load(['jenisPembayaran', 'pembayaran']);

        return view('Parent.pembayaran.show', compact('tagihan'));
    }

    public function bayar(Request $request, Tagihan $tagihan)
    {
        $parent = auth()->user();

        abort_if(
            !$parent->children->pluck('id')->contains($tagihan->student_id),
            403
        );

        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan sudah lunas.');
        }

        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $bukti = $request->file('bukti_bayar')
            ->store('bukti-bayar', 'public');

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'jumlah_bayar' => $tagihan->jumlah,
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_bayar' => now(),
            'bukti_bayar' => $bukti,
            'status_verifikasi' => 'pending',
        ]);

        return redirect()
            ->route('Parent.pembayaran.index')
            ->with('success', 'Pembayaran berhasil, menunggu verifikasi.');
    }

    public function riwayat()
    {
        $parent = auth()->user();

        $pembayaran = Pembayaran::whereHas('tagihan', function ($q) use ($parent) {
            $q->whereIn('student_id', $parent->children->pluck('id'));
        })
            ->with('tagihan.jenisPembayaran')
            ->latest()
            ->paginate(20);

        return view('Parent.pembayaran.riwayat', compact('pembayaran'));
    }

    public function buktiPdf(Pembayaran $pembayaran)
    {
        $parent = auth()->user();

        abort_if(
            !$parent->children->pluck('id')->contains($pembayaran->tagihan->student_id),
            403
        );

        $pdf = Pdf::loadView('Parent.pembayaran.bukti-pdf', compact('pembayaran'));

        return $pdf->download('bukti-pembayaran.pdf');
    }
}

<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('parent.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $query = $student->nilai()
            ->where('is_published', true)
            ->with('mataPelajaran');

        // Filter
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $nilai = $query->get();

        // Hitung rata-rata
        $rataRata = $nilai->avg('nilai_akhir');

        // Group by semester
        $nilaiPerSemester = $nilai->groupBy('semester');

        return view('parent.nilai.index', compact('student', 'nilai', 'rataRata', 'nilaiPerSemester'));
    }

    public function filter(Request $request)
    {
        $student = auth()->user()->student;

        $query = $student->nilai()
            ->where('is_published', true)
            ->with('mataPelajaran');

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $nilai = $query->get();
        $rataRata = $nilai->avg('nilai_akhir');

        return response()->json([
            'success' => true,
            'nilai' => $nilai,
            'rata_rata' => round($rataRata, 2)
        ]);
    }

    public function exportPdf(Request $request)
    {
        $student = auth()->user()->student;

        $query = $student->nilai()
            ->where('is_published', true)
            ->with('mataPelajaran');

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $nilai = $query->get();
        $rataRata = $nilai->avg('nilai_akhir');

        $pdf = Pdf::loadView('parent.nilai.pdf', compact('student', 'nilai', 'rataRata'));

        return $pdf->download('raport-' . $student->nama_lengkap . '.pdf');
    }
}

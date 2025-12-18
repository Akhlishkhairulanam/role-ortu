<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NilaiController extends Controller
{
    public function index()
    {
        $parent = auth()->user();

        $children = $parent->children()
            ->with('nilai.mataPelajaran')
            ->get();

        $nilai = $children
            ->flatMap(fn($anak) => $anak->nilai)
            ->where('is_published', true);

        $rataRata = round($nilai->avg('nilai_akhir'), 2);
        $nilaiPerSemester = $nilai->groupBy('semester');

        return view('Parent.nilai.index', compact(
            'children',
            'nilai',
            'rataRata',
            'nilaiPerSemester'
        ));
    }

    public function filter(Request $request)
    {
        $parent = auth()->user();

        $nilai = $parent->children()
            ->with(['nilai' => function ($q) use ($request) {
                $q->where('is_published', true);

                if ($request->filled('semester')) {
                    $q->where('semester', $request->semester);
                }

                if ($request->filled('tahun_ajaran')) {
                    $q->where('tahun_ajaran', $request->tahun_ajaran);
                }
            }, 'nilai.mataPelajaran'])
            ->get()
            ->flatMap(fn($anak) => $anak->nilai);

        return response()->json([
            'success' => true,
            'nilai' => $nilai,
            'rata_rata' => round($nilai->avg('nilai_akhir'), 2),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $parent = auth()->user();

        $children = $parent->children()
            ->with(['nilai' => function ($q) use ($request) {
                $q->where('is_published', true);

                if ($request->filled('semester')) {
                    $q->where('semester', $request->semester);
                }

                if ($request->filled('tahun_ajaran')) {
                    $q->where('tahun_ajaran', $request->tahun_ajaran);
                }
            }, 'nilai.mataPelajaran'])
            ->get();

        $nilai = $children->flatMap(fn($anak) => $anak->nilai);
        $rataRata = round($nilai->avg('nilai_akhir'), 2);

        $pdf = Pdf::loadView('Parent.nilai.pdf', compact(
            'children',
            'nilai',
            'rataRata'
        ));

        return $pdf->download('raport-anak.pdf');
    }
}

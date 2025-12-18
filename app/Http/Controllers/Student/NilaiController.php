<?php

// app/Http\Controllers\Student\NilaiController.php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        $nilai = $student->nilai()
            ->where('is_published', true)
            ->with('mataPelajaran')
            ->orderBy('mata_pelajaran_id')
            ->orderBy('semester')
            ->get();

        // Group by mata pelajaran dan semester
        $nilaiGrouped = $nilai->groupBy(['mata_pelajaran_id', 'semester']);

        // Hitung rata-rata per semester
        $rataRataPerSemester = [];
        foreach ($nilai->groupBy('semester') as $semester => $items) {
            $rataRataPerSemester[$semester] = $items->avg('nilai');
        }

        return view('student.nilai.index', compact('nilaiGrouped', 'rataRataPerSemester'));
    }

    public function raportPdf(Request $request)
    {
        $student = Auth::user()->student;

        $nilai = $student->nilai()
            ->where('is_published', true)
            ->with('mataPelajaran')
            ->get();

        $semester = $request->semester ?? '1';
        $tahunAjaran = $request->tahun_ajaran ?? now()->year . '/' . (now()->year + 1);

        $pdf = PDF::loadView('student.nilai.raport-pdf', [
            'student' => $student,
            'nilai' => $nilai->where('semester', $semester),
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran
        ]);

        return $pdf->download('raport-' . $student->nis . '-semester-' . $semester . '.pdf');
    }
}

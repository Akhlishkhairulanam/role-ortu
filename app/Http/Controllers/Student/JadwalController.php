<?php

// app/Http\Controllers/Student/JadwalController.php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        $query = $student->jadwal()
            ->with(['mataPelajaran', 'guru', 'kelas'])
            ->orderBy('hari')
            ->orderBy('jam_mulai');

        // Filter hari
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        // Filter semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter tahun ajaran
        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $jadwal = $query->get();

        // Group by hari untuk tampilan
        $jadwalGrouped = $jadwal->groupBy('hari');

        return view('student.jadwal.index', compact('jadwalGrouped'));
    }
}

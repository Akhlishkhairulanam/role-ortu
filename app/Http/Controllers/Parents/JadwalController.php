<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $parent = auth()->user();

        $jadwal = $parent->children()
            ->with(['kelas.jadwal.mataPelajaran', 'kelas.jadwal.guru'])
            ->get()
            ->flatMap(fn($anak) => $anak->kelas?->jadwal ?? collect());

        return view('Parent.jadwal.index', compact('jadwal'));
    }

    public function filter(Request $request)
    {
        // AJAX endpoint untuk filter jadwal
        $student = auth()->user()->student;
        $kelas = $student->kelas;

        $query = $kelas->jadwal()
            ->with(['mataPelajaran', 'guru']);

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwal = $query->orderBy('hari')->orderBy('jam_mulai')->get();

        return response()->json([
            'success' => true,
            'jadwal' => $jadwal->groupBy('hari')
        ]);
    }
}

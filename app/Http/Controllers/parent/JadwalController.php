<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('parent.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $kelas = $student->kelas;

        // Get current semester & tahun ajaran (bisa dari setting atau hardcode)
        $semester = 'Ganjil'; // adjust
        $tahunAjaran = '2024/2025'; // adjust

        $query = $kelas->jadwal()
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->with(['mataPelajaran', 'guru']);

        // Filter berdasarkan hari
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwal = $query->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        // Group jadwal by hari
        $jadwalPerHari = $jadwal->groupBy('hari');

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('parent.jadwal.index', compact('jadwalPerHari', 'hari', 'student', 'semester', 'tahunAjaran'));
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

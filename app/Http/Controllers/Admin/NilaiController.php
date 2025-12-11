<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Student;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Nilai::with(['student', 'mataPelajaran']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $nilai = $query->paginate(20);
        $students = Student::where('status', 'aktif')->get();

        return view('admin.nilai.index', compact('nilai', 'students'));
    }

    public function create()
    {
        $students = Student::where('status', 'aktif')->get();
        $mataPelajaran = MataPelajaran::all();

        return view('admin.nilai.create', compact('students', 'mataPelajaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'semester' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_ulangan' => 'required|numeric|min:0|max:100',
            'nilai_pts' => 'required|numeric|min:0|max:100',
            'nilai_pas' => 'required|numeric|min:0|max:100',
            'catatan_guru' => 'nullable|string',
        ]);

        $nilai = Nilai::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($nilai)
            ->log('Menambahkan nilai siswa');

        return redirect()->route('admin.nilai.index')
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function publish(Nilai $nilai)
    {
        $nilai->update(['is_published' => true]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($nilai)
            ->log('Mempublish nilai siswa: ' . $nilai->student->nama_lengkap);

        return back()->with('success', 'Nilai berhasil dipublish.');
    }

    public function exportPdf(Request $request)
    {
        $nilai = Nilai::with(['student', 'mataPelajaran'])
            ->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))
            ->when($request->semester, fn($q) => $q->where('semester', $request->semester))
            ->get();

        $pdf = Pdf::loadView('admin.nilai.pdf', compact('nilai'));
        return $pdf->download('laporan-nilai.pdf');
    }
}

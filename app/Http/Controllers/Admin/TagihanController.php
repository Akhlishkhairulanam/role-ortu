<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Student;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tagihan::with(['student', 'jenisPembayaran']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tagihan = $query->latest()->paginate(20);
        $students = Student::where('status', 'aktif')->get();

        return view('admin.tagihan.index', compact('tagihan', 'students'));
    }

    public function create()
    {
        $students = Student::where('status', 'aktif')->get();
        $jenisPembayaran = JenisPembayaran::all();

        return view('admin.tagihan.create', compact('students', 'jenisPembayaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayaran,id',
            'bulan' => 'nullable|string',
            'tahun' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'nullable|date',
        ]);

        Tagihan::create($validated);

        return redirect()->route('admin.tagihan.index')
            ->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function edit(Tagihan $tagihan)
    {
        $students = Student::where('status', 'aktif')->get();
        $jenisPembayaran = JenisPembayaran::all();

        return view('admin.tagihan.edit', compact('tagihan', 'students', 'jenisPembayaran'));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayaran,id',
            'bulan' => 'nullable|string',
            'tahun' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:belum_lunas,lunas',
            'tanggal_jatuh_tempo' => 'nullable|date',
        ]);

        $tagihan->update($validated);

        return redirect()->route('admin.tagihan.index')
            ->with('success', 'Tagihan berhasil diupdate.');
    }

    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return redirect()->route('admin.tagihan.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}

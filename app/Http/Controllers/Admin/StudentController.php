<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['kelas', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->paginate(20);
        $kelas = Kelas::all();

        return view('admin.students.index', compact('students', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.students.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:students,nis',
            'nisn' => 'required|unique:students,nisn',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telp' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_masuk' => 'required|string',
        ]);

        $student = Student::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($student)
            ->log('Menambahkan siswa baru: ' . $student->nama_lengkap);

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $kelas = Kelas::all();
        return view('admin.students.edit', compact('student', 'kelas'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:students,nis,' . $student->id,
            'nisn' => 'required|unique:students,nisn,' . $student->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telp' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
        ]);

        $student->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($student)
            ->log('Mengupdate data siswa: ' . $student->nama_lengkap);

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diupdate.');
    }

    public function destroy(Student $student)
    {
        $nama = $student->nama_lengkap;

        // Hapus akun orang tua jika ada
        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Menghapus siswa: ' . $nama);

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function createParentAccount(Student $student)
    {
        if ($student->user) {
            return back()->with('error', 'Akun orang tua sudah ada.');
        }

        // Generate password default
        $defaultPassword = 'password123'; // atau generate random

        $user = User::create([
            'name' => 'Orang Tua ' . $student->nama_lengkap,
            'username' => $student->nis,
            'nis' => $student->nis,
            'password' => Hash::make($defaultPassword),
            'role' => 'Parent',
            'is_active' => true,
        ]);

        $student->update(['user_id' => $user->id]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Membuat akun orang tua untuk siswa: ' . $student->nama_lengkap);

        return back()->with('success', 'Akun orang tua berhasil dibuat. Password default: ' . $defaultPassword);
    }

    public function resetPassword(Student $student)
    {
        if (!$student->user) {
            return back()->with('error', 'Siswa belum memiliki akun orang tua.');
        }

        $newPassword = 'password123'; // atau generate random

        $student->user->update([
            'password' => Hash::make($newPassword)
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($student->user)
            ->log('Reset password akun orang tua siswa: ' . $student->nama_lengkap);

        return back()->with('success', 'Password berhasil direset. Password baru: ' . $newPassword);
    }
}

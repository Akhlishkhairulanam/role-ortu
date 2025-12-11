<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->paginate(20);
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $waliKelas = User::where('role', 'admin')->get();
        return view('admin.kelas.create', compact('waliKelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string',
            'jurusan' => 'nullable|string',
            'tahun_ajaran' => 'required|string',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $waliKelas = User::where('role', 'admin')->get();
        return view('admin.kelas.edit', compact('kela', 'waliKelas'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string',
            'jurusan' => 'nullable|string',
            'tahun_ajaran' => 'required|string',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $kela->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diupdate.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}

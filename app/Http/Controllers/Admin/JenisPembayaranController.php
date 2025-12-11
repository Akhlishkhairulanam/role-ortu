<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;

class JenisPembayaranController extends Controller
{
    public function index()
    {
        $jenisPembayaran = JenisPembayaran::paginate(20);
        return view('admin.jenis-pembayaran.index', compact('jenisPembayaran'));
    }

    public function create()
    {
        return view('admin.jenis-pembayaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pembayaran' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tipe' => 'required|in:bulanan,sekali',
            'keterangan' => 'nullable|string',
        ]);

        JenisPembayaran::create($validated);

        return redirect()->route('admin.jenis-pembayaran.index')
            ->with('success', 'Jenis pembayaran berhasil ditambahkan.');
    }

    public function edit(JenisPembayaran $jenisPembayaran)
    {
        return view('admin.jenis-pembayaran.edit', compact('jenisPembayaran'));
    }

    public function update(Request $request, JenisPembayaran $jenisPembayaran)
    {
        $validated = $request->validate([
            'nama_pembayaran' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tipe' => 'required|in:bulanan,sekali',
            'keterangan' => 'nullable|string',
        ]);

        $jenisPembayaran->update($validated);

        return redirect()->route('admin.jenis-pembayaran.index')
            ->with('success', 'Jenis pembayaran berhasil diupdate.');
    }

    public function destroy(JenisPembayaran $jenisPembayaran)
    {
        $jenisPembayaran->delete();
        return redirect()->route('admin.jenis-pembayaran.index')
            ->with('success', 'Jenis pembayaran berhasil dihapus.');
    }
}

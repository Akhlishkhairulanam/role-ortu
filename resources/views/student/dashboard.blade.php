@extends('layouts.student')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Halo, {{ $student->nama_lengkap }} 👋</h4>
                    <p class="mb-0 text-muted">
                        NIS: {{ $student->nis }} |
                        Kelas: {{ $student->kelas->nama ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- TAGIHAN --}}
        <div class="col-md-4 mb-3">
            <div class="card border-danger shadow-sm">
                <div class="card-body">
                    <h6 class="text-danger">Tagihan Belum Lunas</h6>
                    <h3>{{ $tagihanBelumLunas }}</h3>
                </div>
            </div>
        </div>

        {{-- STATUS PEMBAYARAN --}}
        <div class="col-md-4 mb-3">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="text-info">Pembayaran Terakhir</h6>
                    @if ($pembayaranTerakhir)
                        <span
                            class="badge bg-{{ $pembayaranTerakhir->tagihan->status == 'lunas' ? 'success' : 'warning' }}">
                            {{ strtoupper($pembayaranTerakhir->tagihan->status) }}
                        </span>
                    @else
                        <span class="badge bg-secondary">Belum ada</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="col-md-4 mb-3">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h6>Status Siswa</h6>
                    <span class="badge bg-success">AKTIF</span>
                </div>
            </div>
        </div>
    </div>
@endsection

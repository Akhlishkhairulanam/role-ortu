{{-- resources/views/layouts/parent.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Portal Orang Tua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-2 sidebar p-0">
                <div class="p-4 text-center border-bottom border-light">
                    <i class="fas fa-graduation-cap fa-3x mb-2"></i>
                    <h5>Portal Orang Tua</h5>
                </div>
                <nav class="nav flex-column p-3">
                    <a class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}" href="{{ route('parent.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('parent.profil-anak') ? 'active' : '' }}" href="{{ route('parent.profil-anak') }}">
                        <i class="fas fa-user me-2"></i> Profil Anak
                    </a>
                    <a class="nav-link {{ request()->routeIs('parent.jadwal.*') ? 'active' : '' }}" href="{{ route('parent.jadwal.index') }}">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Pelajaran
                    </a>
                    <a class="nav-link {{ request()->routeIs('parent.nilai.*') ? 'active' : '' }}" href="{{ route('parent.nilai.index') }}">
                        <i class="fas fa-chart-line me-2"></i> Nilai & Raport
                    </a>
                    <a class="nav-link {{ request()->routeIs('parent.pembayaran.*') ? 'active' : '' }}" href="{{ route('parent.pembayaran.index') }}">
                        <i class="fas fa-money-bill-wave me-2"></i> Pembayaran
                    </a>
                    <hr class="bg-light">
                    <a class="nav-link {{ request()->routeIs('parent.ganti-password') ? 'active' : '' }}" href="{{ route('parent.ganti-password') }}">
                        <i class="fas fa-key me-2"></i> Ganti Password
                    </a>
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </nav>
            </div>

            {{-- Main Content --}}
            <div class="col-md-10 p-0">
                {{-- Top Navbar --}}
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">@yield('title')</span>
                        <div class="ms-auto">
                            <span class="me-3">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </span>
                            <span class="badge bg-primary">Orang Tua</span>
                        </div>
                    </div>
                </nav>

                {{-- Content --}}
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

{{-- resources/views/parent/dashboard.blade.php --}}
@extends('layouts.parent')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    {{-- Info Cards --}}
    <div class="col-md-3 mb-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <h6 class="card-title">Nama Siswa</h6>
                <h4>{{ $student->nama_lengkap }}</h4>
                <small>{{ $student->kelas->nama_kelas }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Tagihan Belum Lunas</h6>
                <h4>{{ $tagihan_belum_lunas }}</h4>
                <small>Total: Rp {{ number_format($total_tagihan, 0, ',', '.') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Nilai Terbaru</h6>
                <h4>{{ $nilai_terbaru->count() }}</h4>
                <small>Mata Pelajaran</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Jadwal Hari Ini</h6>
                <h4>{{ $jadwal_hari_ini->count() }}</h4>
                <small>Mata Pelajaran</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Jadwal Hari Ini --}}
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-day me-2"></i>Jadwal Hari Ini - {{ now()->locale('id')->dayName }}
            </div>
            <div class="card-body">
                @if($jadwal_hari_ini->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Ruangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwal_hari_ini as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</td>
                                    <td><strong>{{ $jadwal->mataPelajaran->nama_mapel }}</strong></td>
                                    <td>{{ $jadwal->guru->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $jadwal->ruangan }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>Tidak ada jadwal hari ini
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>Menu Cepat
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('parent.jadwal.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Lihat Semua Jadwal
                    </a>
                    <a href="{{ route('parent.nilai.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-chart-line me-2"></i>Lihat Nilai & Raport
                    </a>
                    <a href="{{ route('parent.pembayaran.index') }}" class="btn btn-outline-warning">
                        <i class="fas fa-money-bill-wave me-2"></i>Pembayaran
                    </a>
                    <a href="{{ route('parent.profil-anak') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user me-2"></i>Profil Anak
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Nilai Terbaru --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-star me-2"></i>Nilai Terbaru
            </div>
            <div class="card-body">
                @if($nilai_terbaru->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Tugas</th>
                                    <th>Ulangan</th>
                                    <th>PTS</th>
                                    <th>PAS</th>
                                    <th>Nilai Akhir</th>
                                    <th>Predikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilai_terbaru as $nilai)
                                <tr>
                                    <td><strong>{{ $nilai->mataPelajaran->nama_mapel }}</strong></td>
                                    <td>{{ $nilai->nilai_tugas }}</td>
                                    <td>{{ $nilai->nilai_ulangan }}</td>
                                    <td>{{ $nilai->nilai_pts }}</td>
                                    <td>{{ $nilai->nilai_pas }}</td>
                                    <td><strong>{{ number_format($nilai->nilai_akhir, 2) }}</strong></td>
                                    <td><span class="badge bg-{{ $nilai->predikat == 'A' ? 'success' : ($nilai->predikat == 'B' ? 'primary' : 'warning') }}">{{ $nilai->predikat }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('parent.nilai.index') }}" class="btn btn-sm btn-primary">
                            Lihat Semua Nilai <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>Belum ada nilai yang dipublish
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
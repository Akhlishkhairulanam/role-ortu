@extends('layouts.Parent')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        @foreach ($children as $anak)
            <div class="alert alert-info mb-3">
                <strong>{{ $anak->nama_lengkap }}</strong> |
                NIS: {{ $anak->nis }} |
                Kelas: {{ $anak->kelas->nama ?? '-' }}
            </div>
        @endforeach

        {{-- Kartu Statistik --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Tagihan Belum Lunas</div>
                <div class="card-body text-center">
                    <h3>{{ $tagihan_belum_lunas }}</h3>
                    <p class="text-muted">Jumlah Tagihan</p>
                    <h5>Rp {{ number_format($total_tagihan, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>

        {{-- Jadwal Hari Ini --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Jadwal Hari Ini</div>
                <div class="card-body">
                    @if ($jadwal_hari_ini->count() == 0)
                        <p class="text-muted">Tidak ada jadwal hari ini.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($jadwal_hari_ini as $j)
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $j->mataPelajaran->nama }}</strong><br>
                                        <small>{{ $j->guru->nama }}</small>
                                    </div>
                                    <div>
                                        {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Nilai Terbaru --}}
    <div class="card">
        <div class="card-header">Nilai Terbaru</div>
        <div class="card-body">

            @if ($nilai_terbaru->count() == 0)
                <p class="text-muted">Belum ada nilai terbaru.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Nilai</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nilai_terbaru as $n)
                            <tr>
                                <td>{{ $n->mataPelajaran->nama }}</td>
                                <td>{{ $n->nilai }}</td>
                                <td>{{ $n->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>

@endsection

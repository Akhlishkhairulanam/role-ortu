@extends('layouts.student')

@section('title', 'Jadwal Pelajaran')

@section('content')
    <h4 class="mb-3">Jadwal Pelajaran</h4>

    @forelse($jadwalGrouped as $hari => $items)
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white text-uppercase">
                {{ $hari }}
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <tr>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                    </tr>
                    @foreach ($items as $jadwal)
                        <tr>
                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td>{{ $jadwal->mataPelajaran->nama }}</td>
                            <td>{{ $jadwal->guru->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Tidak ada jadwal.</div>
    @endforelse
@endsection

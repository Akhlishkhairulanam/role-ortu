@extends('layouts.student')

@section('title', 'Nilai Siswa')

@section('content')
    <h4 class="mb-3">Nilai Akademik</h4>

    @forelse($nilaiGrouped as $semester => $items)
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-success text-white">
                Semester {{ $semester }}
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Jenis</th>
                        <th>Nilai</th>
                    </tr>
                    @foreach ($items as $nilai)
                        <tr>
                            <td>{{ $nilai->mataPelajaran->nama }}</td>
                            <td>{{ ucfirst($nilai->jenis_nilai) }}</td>
                            <td>
                                <span class="badge bg-{{ $nilai->nilai >= 75 ? 'success' : 'danger' }}">
                                    {{ $nilai->nilai }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endforeach

@endsection

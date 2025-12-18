@extends('layouts.Parent')

@section('title', 'Profil Anak')

@section('content')

    <div class="card mb-4">
        <div class="card-header">Data Siswa</div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Nama</th>
                    <td>{{ $student->nama }}</td>
                </tr>
                <tr>
                    <th>NISN</th>
                    <td>{{ $student->nisn }}</td>
                </tr>
                <tr>
                    <th>Kelas</th>
                    <td>{{ $student->kelas->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $student->alamat }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Riwayat Absensi</div>
        <div class="card-body">
            {{-- Dummy untuk testing --}}
            @php
                $absensi = [
                    ['tanggal' => '2025-01-02', 'status' => 'Hadir'],
                    ['tanggal' => '2025-01-03', 'status' => 'Sakit'],
                    ['tanggal' => '2025-01-04', 'status' => 'Hadir'],
                ];
            @endphp

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensi as $a)
                        <tr>
                            <td>{{ $a['tanggal'] }}</td>
                            <td>{{ $a['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@extends('layouts.Parent')

@section('title', 'Jadwal Pelajaran')

@section('content')
    <div class="card">
        <div class="card-header">Jadwal Pelajaran</div>
        <div class="card-body">
            {{-- Jika controller kirim $jadwalPerHari gunakan itu, jika kirim $jadwal gunakan that --}}
            @if (isset($jadwalPerHari) && count($jadwalPerHari))
                @foreach ($hari as $h)
                    <h5 class="mt-3">{{ $h }}</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalPerHari[$h] ?? [] as $j)
                                <tr>
                                    <td>{{ optional($j->jam_mulai)->format ? $j->jam_mulai->format('H:i') : $j->jam_mulai ?? '-' }}
                                        -
                                        {{ optional($j->jam_selesai)->format ? $j->jam_selesai->format('H:i') : $j->jam_selesai ?? '-' }}
                                    </td>
                                    <td>{{ $j->mataPelajaran->nama_mapel ?? ($j->mataPelajaran->nama ?? ($j['mapel'] ?? '-')) }}
                                    </td>
                                    <td>{{ $j->guru->name ?? ($j['guru'] ?? '-') }}</td>
                                    <td>{{ $j->ruangan ?? ($j['ruangan'] ?? '-') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Tidak ada jadwal untuk {{ $h }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endforeach
            @elseif(isset($jadwal) && count($jadwal))
                <table class="table">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal as $j)
                            <tr>
                                <td>{{ $j['hari'] ?? ($j->hari ?? '-') }}</td>
                                <td>{{ $j['jam'] ?? ($j->jam_mulai ?? '-') }}</td>
                                <td>{{ $j['mapel'] ?? ($j->mataPelajaran->nama_mapel ?? '-') }}</td>
                                <td>{{ $j['guru'] ?? ($j->guru->name ?? '-') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">Belum ada jadwal.</div>
            @endif
        </div>
    </div>
@endsection

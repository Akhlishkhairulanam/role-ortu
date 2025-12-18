@extends('layouts.Parent')

@section('title', 'Nilai & Raport')

@section('content')
    <div class="card">
        <div class="card-header">Nilai Akademik</div>
        <div class="card-body">
            @if (isset($nilai) && count($nilai))
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nilai as $n)
                            <tr>
                                <td>{{ $n['mapel'] ?? ($n->mataPelajaran->nama_mapel ?? '-') }}</td>
                                <td>{{ $n['nilai'] ?? ($n->nilai_akhir ?? '-') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif(isset($nilaiPerSemester) && count($nilaiPerSemester))
                @foreach ($nilaiPerSemester as $semester => $items)
                    <h5>{{ $semester }}</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mapel</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $it)
                                <tr>
                                    <td>{{ $it->mataPelajaran->nama_mapel ?? '-' }}</td>
                                    <td>{{ $it->nilai_akhir ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @else
                <div class="alert alert-info">Belum ada nilai.</div>
            @endif
        </div>
    </div>
@endsection

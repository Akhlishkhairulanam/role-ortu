@extends('layouts.student')

@section('title', 'Status Pembayaran')

@section('content')
    <h4 class="mb-3">Status Pembayaran</h4>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <tr>
                    <th>Jenis</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Status</th>
                </tr>
                @foreach ($pembayaranGrouped as $jenis => $items)
                    @foreach ($items as $pembayaran)
                        <tr>
                            <td>{{ $jenis }}</td>
                            <td>{{ $pembayaran->tagihan->bulan ?? '-' }}</td>
                            <td>{{ $pembayaran->tagihan->tahun }}</td>
                            <td>
                                <span class="badge bg-{{ $pembayaran->tagihan->status == 'lunas' ? 'success' : 'warning' }}">
                                    {{ strtoupper($pembayaran->tagihan->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
    </div>
@endsection

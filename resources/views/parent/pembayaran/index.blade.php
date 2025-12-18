@extends('layouts.Parent')

@section('title', 'Pembayaran')

@section('content')
    <div class="card mb-4">
        <div class="card-header">Tagihan Pembayaran</div>
        <div class="card-body">
            {{-- Controller sebaiknya mengirim $tagihan (collection) --}}
            @if (isset($tagihan) && count($tagihan))
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tagihan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tagihan as $t)
                            <tr>
                                <td>{{ $t->jenisPembayaran->nama_pembayaran }}</td>
                                <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                                <td id="status-{{ $t->id }}">
                                    @if ($t->status === 'lunas')
                                        <span class="badge bg-success">LUNAS</span>
                                    @elseif ($t->status === 'menunggu_verifikasi')
                                        <span class="badge bg-warning text-dark">MENUNGGU</span>
                                    @elseif ($t->status === 'ditolak')
                                        <span class="badge bg-danger">DITOLAK</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if (($t->status ?? '') !== 'lunas')
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalBayar{{ $t->id }}">Bayar</button>
                                    @else
                                        <span class="badge bg-success">LUNAS</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal untuk tiap tagihan --}}
                            <div class="modal fade" id="modalBayar{{ $t->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('Parent.pembayaran.bayar', $t) }}" method="POST"
                                        enctype="multipart/form-data" class="modal-content">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Bayar {{ $t->nama_tagihan ?? $t->nama }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Rekening Tujuan</label>
                                                <select name="bank" class="form-control" required>
                                                    {{-- Hardcode rekening sekolah; bisa di-load dari DB --}}
                                                    <option value="BCA">BCA - 1234567890 (Sekolah)</option>
                                                    <option value="BRI">BRI - 0987654321 (Sekolah)</option>
                                                    <option value="Mandiri">Mandiri - 135791113 (Sekolah)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Upload Bukti Transfer (jpg/png/pdf)</label>
                                                <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success w-100">Kirim Bukti &amp;
                                                Bayar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">Tidak ada tagihan.</div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // simple polling untuk update status (2 detik)
            setInterval(() => {
                fetch("{{ url('/parent/pembayaran/status') }}", {
                        credentials: 'same-origin'
                    })
                    .then(r => r.json())
                    .then(data => {
                        data.forEach(item => {
                            const el = document.getElementById('status-' + item.id);
                            if (el) el.innerText = item.status ? item.status.toUpperCase() : '-';
                        });
                    })
                    .catch(err => console.error(err));
            }, 2000);
        </script>
    @endpush
@endsection

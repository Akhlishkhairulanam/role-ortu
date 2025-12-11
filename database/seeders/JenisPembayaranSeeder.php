<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPembayaran;

class JenisPembayaranSeeder extends Seeder
{
    public function run()
    {
        $jenis = [
            ['nama_pembayaran' => 'SPP', 'nominal' => 500000, 'tipe' => 'bulanan', 'keterangan' => 'Sumbangan Pembinaan Pendidikan per bulan'],
            ['nama_pembayaran' => 'Uang Gedung', 'nominal' => 5000000, 'tipe' => 'sekali', 'keterangan' => 'Uang gedung untuk siswa baru'],
            ['nama_pembayaran' => 'Seragam', 'nominal' => 750000, 'tipe' => 'sekali', 'keterangan' => 'Paket seragam lengkap'],
            ['nama_pembayaran' => 'Kegiatan Ekstrakurikuler', 'nominal' => 200000, 'tipe' => 'sekali', 'keterangan' => 'Biaya kegiatan ekstrakurikuler per semester'],
            ['nama_pembayaran' => 'Praktek Laboratorium', 'nominal' => 300000, 'tipe' => 'sekali', 'keterangan' => 'Biaya praktek lab per semester'],
        ];

        foreach ($jenis as $j) {
            JenisPembayaran::create($j);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $kelas = [
            ['nama_kelas' => 'X IPA 1', 'tingkat' => 'X', 'jurusan' => 'IPA', 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'X IPA 2', 'tingkat' => 'X', 'jurusan' => 'IPA', 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'X IPS 1', 'tingkat' => 'X', 'jurusan' => 'IPS', 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPA 1', 'tingkat' => 'XI', 'jurusan' => 'IPA', 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPS 1', 'tingkat' => 'XI', 'jurusan' => 'IPS', 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPA 1', 'tingkat' => 'XII', 'jurusan' => 'IPA', 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPS 1', 'tingkat' => 'XII', 'jurusan' => 'IPS', 'tahun_ajaran' => '2024/2025'],
        ];

        foreach ($kelas as $k) {
            Kelas::create($k);
        }
    }
}

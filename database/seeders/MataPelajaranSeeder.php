<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $mapel = [
            ['kode_mapel' => 'MAT', 'nama_mapel' => 'Matematika', 'kkm' => 75],
            ['kode_mapel' => 'BIN', 'nama_mapel' => 'Bahasa Indonesia', 'kkm' => 75],
            ['kode_mapel' => 'BING', 'nama_mapel' => 'Bahasa Inggris', 'kkm' => 75],
            ['kode_mapel' => 'FIS', 'nama_mapel' => 'Fisika', 'kkm' => 75],
            ['kode_mapel' => 'KIM', 'nama_mapel' => 'Kimia', 'kkm' => 75],
            ['kode_mapel' => 'BIO', 'nama_mapel' => 'Biologi', 'kkm' => 75],
            ['kode_mapel' => 'SEJ', 'nama_mapel' => 'Sejarah', 'kkm' => 75],
            ['kode_mapel' => 'GEO', 'nama_mapel' => 'Geografi', 'kkm' => 75],
            ['kode_mapel' => 'EKO', 'nama_mapel' => 'Ekonomi', 'kkm' => 75],
            ['kode_mapel' => 'SOS', 'nama_mapel' => 'Sosiologi', 'kkm' => 75],
            ['kode_mapel' => 'PJOK', 'nama_mapel' => 'Pendidikan Jasmani', 'kkm' => 75],
            ['kode_mapel' => 'PAI', 'nama_mapel' => 'Pendidikan Agama Islam', 'kkm' => 75],
        ];

        foreach ($mapel as $m) {
            MataPelajaran::create($m);
        }
    }
}

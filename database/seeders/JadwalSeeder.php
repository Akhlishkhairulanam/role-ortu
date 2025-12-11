<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        // Jadwal untuk Kelas X IPA 1
        $jadwalXIPA1 = [
            // Senin
            ['kelas_id' => 1, 'mata_pelajaran_id' => 1, 'guru_id' => 2, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'ruangan' => 'R.101', 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025'],
            ['kelas_id' => 1, 'mata_pelajaran_id' => 4, 'guru_id' => 2, 'hari' => 'Senin', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'ruangan' => 'R.101', 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025'],
            ['kelas_id' => 1, 'mata_pelajaran_id' => 2, 'guru_id' => 3, 'hari' => 'Senin', 'jam_mulai' => '10:15', 'jam_selesai' => '11:45', 'ruangan' => 'R.101', 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025'],

            // Selasa
            ['kelas_id' => 1, 'mata_pelajaran_id' => 5, 'guru_id' => 2, 'hari' => 'Selasa', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'ruangan' => 'Lab Kimia', 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025'],
            ['kelas_id' => 1, 'mata_pelajaran_id' => 6, 'guru_id' => 3, 'hari' => 'Selasa', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'ruangan' => 'Lab Bio', 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025'],
            ['kelas_id' => 1, 'mata_pelajaran_id' => 3, 'guru_id' => 2, 'hari' => 'Selasa', 'jam_mulai' => '10:15', 'jam_selesai' => '11:45', 'ruangan' => 'R.101', 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025'],
        ];

        foreach ($jadwalXIPA1 as $j) {
            Jadwal::create($j);
        }
    }
}

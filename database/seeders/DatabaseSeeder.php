<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            KelasSeeder::class,
            MataPelajaranSeeder::class,
            StudentSeeder::class,
            JadwalSeeder::class,
            JenisPembayaranSeeder::class,
        ]);
    }
}

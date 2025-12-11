<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run()
    {
        // Siswa 1
        $user1 = User::create([
            'name' => 'Orang Tua Ahmad Rizki',
            'username' => '2024001',
            'nis' => '2024001',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            'is_active' => true,
        ]);

        Student::create([
            'nis' => '2024001',
            'nisn' => '0051234567',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2008-05-15',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta',
            'no_telp' => '081234567890',
            'kelas_id' => 1, // X IPA 1
            'tahun_masuk' => '2024',
            'status' => 'aktif',
            'user_id' => $user1->id,
        ]);

        // Siswa 2
        $user2 = User::create([
            'name' => 'Orang Tua Siti Nurhaliza',
            'username' => '2024002',
            'nis' => '2024002',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            'is_active' => true,
        ]);

        Student::create([
            'nis' => '2024002',
            'nisn' => '0051234568',
            'nama_lengkap' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2008-08-20',
            'alamat' => 'Jl. Sudirman No. 456, Bandung',
            'no_telp' => '081234567891',
            'kelas_id' => 1, // X IPA 1
            'tahun_masuk' => '2024',
            'status' => 'aktif',
            'user_id' => $user2->id,
        ]);

        // Siswa 3
        $user3 = User::create([
            'name' => 'Orang Tua Budi Santoso',
            'username' => '2024003',
            'nis' => '2024003',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            'is_active' => true,
        ]);

        Student::create([
            'nis' => '2024003',
            'nisn' => '0051234569',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2008-03-10',
            'alamat' => 'Jl. Ahmad Yani No. 789, Surabaya',
            'no_telp' => '081234567892',
            'kelas_id' => 3, // X IPS 1
            'tahun_masuk' => '2024',
            'status' => 'aktif',
            'user_id' => $user3->id,
        ]);
    }
}

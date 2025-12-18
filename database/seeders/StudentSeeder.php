<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SISWA 1
        |--------------------------------------------------------------------------
        */

        // Akun Ortu
        $parent1 = User::create([
            'name' => 'Orang Tua Ahmad Rizki',
            'username' => 'ortu_2024001',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            'is_active' => true,
        ]);

        // Akun Siswa (LOGIN PAKAI NIS)
        $studentUser1 = User::create([
            'name' => 'Ahmad Rizki',
            'username' => '2024001', // NIS
            'password' => Hash::make('password123'),
            'role' => 'student',
            'is_active' => true,
        ]);

        // Data Siswa
        Student::create([
            'user_id' => $studentUser1->id,      // ✅ AKUN SISWA
            'parent_user_id' => $parent1->id,    // ✅ AKUN ORTU
            'nis' => '2024001',
            'nisn' => '0051234567',
            'nama_lengkap' => 'Ahmad Rizki',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2008-05-15',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta',
            'no_telp' => '081234567890',
            'kelas_id' => 1,
            'tahun_masuk' => '2024',
            'status' => 'aktif',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SISWA 2
        |--------------------------------------------------------------------------
        */

        $parent2 = User::create([
            'name' => 'Orang Tua Siti Nurhaliza',
            'username' => 'ortu_2024002',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            'is_active' => true,
        ]);

        $studentUser2 = User::create([
            'name' => 'Siti Nurhaliza',
            'username' => '2024002',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'is_active' => true,
        ]);

        Student::create([
            'user_id' => $studentUser2->id,
            'parent_user_id' => $parent2->id,
            'nis' => '2024002',
            'nisn' => '0051234568',
            'nama_lengkap' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2008-08-20',
            'alamat' => 'Jl. Sudirman No. 456, Bandung',
            'no_telp' => '081234567891',
            'kelas_id' => 1,
            'tahun_masuk' => '2024',
            'status' => 'aktif',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SISWA 3
        |--------------------------------------------------------------------------
        */

        $parent3 = User::create([
            'name' => 'Orang Tua Budi Santoso',
            'username' => 'ortu_2024003',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            'is_active' => true,
        ]);

        $studentUser3 = User::create([
            'name' => 'Budi Santoso',
            'username' => '2024003',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'is_active' => true,
        ]);

        Student::create([
            'user_id' => $studentUser3->id,
            'parent_user_id' => $parent3->id,
            'nis' => '2024003',
            'nisn' => '0051234569',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2008-03-10',
            'alamat' => 'Jl. Ahmad Yani No. 789, Surabaya',
            'no_telp' => '081234567892',
            'kelas_id' => 3,
            'tahun_masuk' => '2024',
            'status' => 'aktif',
        ]);
    }
}

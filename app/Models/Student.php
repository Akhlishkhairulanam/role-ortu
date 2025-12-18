<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        // Identitas siswa
        'user_id',              // akun siswa
        'parent_user_id',       // akun orang tua
        'nis',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telp',

        // Akademik
        'kelas_id',
        'tahun_masuk',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /* =======================
     | RELASI USER
     ======================= */

    // Akun siswa
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Akun orang tua
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    /* =======================
     | RELASI AKADEMIK
     ======================= */

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'student_id');
    }

    /* =======================
     | RELASI PEMBAYARAN
     ======================= */

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'student_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'student_id');
    }

    /* =======================
     | JADWAL (VIA KELAS)
     ======================= */

    public function jadwal()
    {
        return $this->hasManyThrough(
            Jadwal::class,
            Kelas::class,
            'id',        // PK di kelas
            'kelas_id',  // FK di jadwal
            'kelas_id',  // FK di student
            'id'         // PK di kelas
        );
    }
    public function isActive()
    {
        return $this->status === 'aktif';
    }
}

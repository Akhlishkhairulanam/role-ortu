<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jurusan',
        'tahun_ajaran',
        'wali_kelas_id'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }
}

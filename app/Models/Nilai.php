<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'student_id',
        'mata_pelajaran_id',
        'semester',
        'tahun_ajaran',
        'nilai_tugas',
        'nilai_ulangan',
        'nilai_pts',
        'nilai_pas',
        'nilai_akhir',
        'predikat',
        'catatan_guru',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    // Auto calculate nilai akhir
    public static function boot()
    {
        Parent::boot();

        static::saving(function ($nilai) {
            $nilai->nilai_akhir = (
                ($nilai->nilai_tugas * 0.2) +
                ($nilai->nilai_ulangan * 0.3) +
                ($nilai->nilai_pts * 0.2) +
                ($nilai->nilai_pas * 0.3)
            );

            // Auto generate predikat
            if ($nilai->nilai_akhir >= 90) {
                $nilai->predikat = 'A';
            } elseif ($nilai->nilai_akhir >= 80) {
                $nilai->predikat = 'B';
            } elseif ($nilai->nilai_akhir >= 70) {
                $nilai->predikat = 'C';
            } else {
                $nilai->predikat = 'D';
            }
        });
    }
}

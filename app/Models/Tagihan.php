<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $fillable = [
        'student_id',
        'jenis_pembayaran_id',
        'bulan',
        'tahun',
        'jumlah',
        'status',
        'tanggal_jatuh_tempo'
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}

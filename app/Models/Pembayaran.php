<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $fillable = [
        'tagihan_id',
        'no_invoice',
        'jumlah_bayar',
        'metode_pembayaran',
        'tanggal_bayar',
        'bukti_bayar',
        'status_verifikasi',
        'catatan'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    protected static function booted()
    {
        static::creating(function ($pembayaran) {
            $pembayaran->no_invoice = 'INV/' . now()->format('Ymd') . '/' . str_pad(
                self::max('id') + 1,
                5,
                '0',
                STR_PAD_LEFT
            );
            $pembayaran->status_verifikasi ??= 'pending';
            $pembayaran->tanggal_bayar ??= now();
        });
    }
}

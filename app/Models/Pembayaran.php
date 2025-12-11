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
        'tanggal_bayar' => 'date',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pembayaran) {
            $pembayaran->no_invoice = 'INV/' . date('Ymd') . '/' . str_pad(Pembayaran::count() + 1, 5, '0', STR_PAD_LEFT);
        });
    }
}

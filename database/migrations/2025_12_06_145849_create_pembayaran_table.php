<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // TABEL JENIS PEMBAYARAN
        Schema::create('jenis_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pembayaran');
            $table->decimal('nominal', 15, 2);
            $table->enum('tipe', ['bulanan', 'sekali'])->default('sekali');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // TABEL TAGIHAN
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();

            // explicit biar aman
            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');

            $table->foreignId('jenis_pembayaran_id')
                ->constrained('jenis_pembayaran')
                ->onDelete('cascade');

            $table->string('bulan')->nullable();
            $table->string('tahun');
            $table->decimal('jumlah', 15, 2);
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->timestamps();
        });

        // TABEL PEMBAYARAN
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            // wajib sebut tabel karena nama tabel kamu 'tagihan' bukan 'tagihans'
            $table->foreignId('tagihan_id')
                ->constrained('tagihan')
                ->onDelete('cascade');

            $table->string('no_invoice')->unique();
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('metode_pembayaran');
            $table->date('tanggal_bayar');
            $table->string('bukti_bayar')->nullable();
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('tagihan');
        Schema::dropIfExists('jenis_pembayaran');
    }
};

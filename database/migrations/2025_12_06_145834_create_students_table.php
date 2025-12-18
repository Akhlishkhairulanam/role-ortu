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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // akun siswa

            $table->foreignId('parent_user_id')
                ->constrained('users')
                ->onDelete('cascade'); // akun ortu

            $table->string('nis')->unique();
            $table->string('nisn')->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_telp')->nullable();
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
            $table->string('tahun_masuk');
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

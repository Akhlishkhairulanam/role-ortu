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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')
                ->constrained('mata_pelajaran')
                ->onDelete('cascade');
            $table->string('semester'); // Ganjil/Genap
            $table->string('tahun_ajaran');
            $table->decimal('nilai_tugas', 5, 2)->default(0);
            $table->decimal('nilai_ulangan', 5, 2)->default(0);
            $table->decimal('nilai_pts', 5, 2)->default(0);
            $table->decimal('nilai_pas', 5, 2)->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->string('predikat')->nullable(); // A, B, C, D
            $table->text('catatan_guru')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};

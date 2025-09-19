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
    Schema::create('sub_kriteria', function (Blueprint $table) {
        $table->id('id_sub_kriteria');
        $table->foreignId('id_kriteria')->constrained('kriteria', 'id_kriteria')->onDelete('cascade');
        $table->string('nama_tampilan'); // Teks yang dilihat pengguna, misal: "> 350 Halaman"
        $table->integer('nilai'); // Nilai penomoran 1-4 yang akan diisi otomatis

        // Kolom untuk aturan TIPE TEKS
        $table->string('nilai_teks')->nullable(); // misal: "Non-Fiksi"

        // Kolom untuk aturan TIPE ANGKA
        $table->string('operator')->nullable(); // misal: '>=', '<=', 'hingga'
        $table->float('nilai_angka_1')->nullable();
        $table->float('nilai_angka_2')->nullable(); // Hanya untuk 'hingga'
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('sub_kriteria');
    }
};

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
        // Relasi untuk tabel 'pertanyaan'
        Schema::table('pertanyaan', function (Blueprint $table) {
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
        });

        // // Relasi untuk tabel 'sub_kriteria'
        // Schema::table('sub_kriteria', function (Blueprint $table) {
        //     $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
        // });

        // Relasi untuk tabel 'bookings'
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });

        // Relasi untuk tabel 'detail_perhitungan'
        Schema::table('detail_perhitungan', function (Blueprint $table) {
            $table->foreign('id_perhitungan')->references('id_perhitungan')->on('perhitungan')->onDelete('cascade');
            $table->foreign('id')->references('id')->on('books')->onDelete('cascade'); // Kolom 'id' merujuk ke books.id
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });

        // Relasi untuk tabel 'normalisasi'
        Schema::table('normalisasi', function (Blueprint $table) {
            $table->foreign('id_detail')->references('id_detail')->on('detail_perhitungan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus foreign key HARUS dilakukan dalam urutan terbalik dari 'up()'
        Schema::table('normalisasi', function (Blueprint $table) {
            $table->dropForeign(['id_detail']);
        });

        Schema::table('detail_perhitungan', function (Blueprint $table) {
            $table->dropForeign(['id_perhitungan']);
            $table->dropForeign(['id']);
            $table->dropForeign(['id_user']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['book_id']);
        });

        // Schema::table('sub_kriteria', function (Blueprint $table) {
        //     $table->dropForeign(['id_kriteria']);
        // });

        Schema::table('pertanyaan', function (Blueprint $table) {
            $table->dropForeign(['id_kriteria']);
        });
    }
};

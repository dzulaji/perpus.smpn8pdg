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
        Schema::create('normalisasi', function (Blueprint $table) {
            $table->id('id_normalisasi');
            $table->unsignedBigInteger('id_detail');
            $table->float('normalisasi');
            $table->float('utilities');

            // $table->foreign('id_detail')->references('id_detail')->on('detail_perhitungan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normalisasi');
    }
};

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
        Schema::create('detail_perhitungan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_perhitungan');
            $table->unsignedBigInteger('id');
            $table->float('skor_akhir');
            $table->unsignedBigInteger('id_user');
            
            // $table->foreign('id_perhitungan')->references('id_perhitungan')->on('perhitungan')->onDelete('cascade');
            // $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_perhitungan');
    }
};

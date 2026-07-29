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
        Schema::table('books', function (Blueprint $table) {
            $table->text('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Kita ubah kembali ke string jika di rollback
            // Namun, jika ada teks panjang, ini bisa error, jadi kita ubah dengan aman atau gunakan DB facade.
            $table->string('description', 255)->change();
        });
    }
};

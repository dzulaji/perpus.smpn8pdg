<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('isbn_issn')->nullable()->change();
            $table->string('content_type')->nullable()->change();
            $table->string('media_type')->nullable()->change();
            $table->string('link')->nullable()->change();
            $table->string('carrier_type')->nullable()->change();
            $table->string('edition')->nullable()->change();
            $table->string('subject')->nullable()->change();
            $table->integer('pages')->nullable()->change();
            $table->string('language')->nullable()->change();
            $table->string('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update null values to empty strings or 0 before making them NOT NULL
        DB::table('books')->whereNull('isbn_issn')->update(['isbn_issn' => '']);
        DB::table('books')->whereNull('content_type')->update(['content_type' => '']);
        DB::table('books')->whereNull('media_type')->update(['media_type' => '']);
        DB::table('books')->whereNull('link')->update(['link' => '']);
        DB::table('books')->whereNull('carrier_type')->update(['carrier_type' => '']);
        DB::table('books')->whereNull('edition')->update(['edition' => '']);
        DB::table('books')->whereNull('subject')->update(['subject' => '']);
        DB::table('books')->whereNull('pages')->update(['pages' => 0]);
        DB::table('books')->whereNull('language')->update(['language' => '']);
        DB::table('books')->whereNull('description')->update(['description' => '']);

        Schema::table('books', function (Blueprint $table) {
            $table->string('isbn_issn')->nullable(false)->change();
            $table->string('content_type')->nullable(false)->change();
            $table->string('media_type')->nullable(false)->change();
            $table->string('link')->nullable(false)->change();
            $table->string('carrier_type')->nullable(false)->change();
            $table->string('edition')->nullable(false)->change();
            $table->string('subject')->nullable(false)->change();
            $table->integer('pages')->nullable(false)->change();
            $table->string('language')->nullable(false)->change();
            $table->string('description')->nullable(false)->change();
        });
    }
};

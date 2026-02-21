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
        // Drop old columns and add new structure
        Schema::table('categories', function (Blueprint $table) {
            // Drop the old string columns if they exist
            $table->dropColumn('jenis_barang', 'kelompok_barang');
        });

        // Add name and update foreign key structure
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->after('kelompok_barang_id');
            $table->text('description')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('jenis_barang')->after('id');
            $table->string('kelompok_barang')->after('jenis_barang');
            $table->dropColumn('name', 'description');
        });
    }
};

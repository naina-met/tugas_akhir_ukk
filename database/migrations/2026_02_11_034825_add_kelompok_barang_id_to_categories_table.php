<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('kelompok_barang_id')
                  ->after('id')
                  ->constrained('kelompok_barang')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['kelompok_barang_id']);
            $table->dropColumn('kelompok_barang_id');
        });
    }
};

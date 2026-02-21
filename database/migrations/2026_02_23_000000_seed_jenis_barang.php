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
        // Check if jenis_barang already has data
        if (DB::table('jenis_barang')->count() === 0) {
            DB::table('jenis_barang')->insert([
                [
                    'name' => 'Modal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Barang Habis Pakai',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('jenis_barang')->whereIn('name', ['Modal', 'Barang Habis Pakai'])->delete();
    }
};

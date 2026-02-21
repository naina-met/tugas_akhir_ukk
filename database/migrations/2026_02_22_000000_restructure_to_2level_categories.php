<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Restructure from 3-level (Jenis > Kelompok > Kategori) to 2-level (Jenis dropdown > Kategori manual)
     */
    public function up(): void
    {
        // Step 1: Drop the foreign key constraint from categories.kelompok_barang_id first
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'kelompok_barang_id')) {
                // Disable foreign key checks for MySQL
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                
                $table->dropForeign(['kelompok_barang_id']);
                
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        });

        // Step 2: Drop kelompok_barang table
        Schema::dropIfExists('kelompok_barang');

        // Step 3: Restructure categories table
        Schema::table('categories', function (Blueprint $table) {
            // Drop old columns
            if (Schema::hasColumn('categories', 'kelompok_barang_id')) {
                $table->dropColumn('kelompok_barang_id');
            }
            if (Schema::hasColumn('categories', 'jenis_barang')) {
                $table->dropColumn('jenis_barang');
            }
            if (Schema::hasColumn('categories', 'kelompok_barang')) {
                $table->dropColumn('kelompok_barang');
            }

            // Add new jenis_barang_id as foreign key (if not exists)
            if (!Schema::hasColumn('categories', 'jenis_barang_id')) {
                $table->foreignId('jenis_barang_id')
                    ->after('id')
                    ->constrained('jenis_barang')
                    ->cascadeOnDelete();
            }

            // Ensure name column exists
            if (!Schema::hasColumn('categories', 'name')) {
                $table->string('name')->after('jenis_barang_id');
            }

            // Ensure description column exists
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop the new structure
            if (Schema::hasColumn('categories', 'jenis_barang_id')) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                $table->dropForeign(['jenis_barang_id']);
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                
                $table->dropColumn('jenis_barang_id');
            }
            if (Schema::hasColumn('categories', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('categories', 'description')) {
                $table->dropColumn('description');
            }

            // Restore old string columns
            $table->string('jenis_barang')->after('id')->nullable();
            $table->string('kelompok_barang')->after('jenis_barang')->nullable();
        });

        // Recreate kelompok_barang table
        Schema::create('kelompok_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_barang_id')->constrained('jenis_barang')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        // Re-add the foreign key for categories
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('kelompok_barang_id')
                ->after('id')
                ->constrained('kelompok_barang')
                ->cascadeOnDelete();
        });
    }
};

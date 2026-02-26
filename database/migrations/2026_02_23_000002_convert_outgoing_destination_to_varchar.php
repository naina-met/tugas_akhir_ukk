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
        Schema::table('stock_outs', function (Blueprint $table) {
            // Change ENUM to VARCHAR to support lowercase values and 'peminjaman'
            $table->string('outgoing_destination', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            // Revert back to ENUM
            $table->enum('outgoing_destination', ['Penjualan', 'Pemakaian internal', 'Rusak'])->change();
        });
    }
};

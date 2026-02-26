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
        // Change date column to datetime in stock_ins table
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->dateTime('date')->change();
        });

        // Change date column to datetime in stock_outs table
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dateTime('date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to date in stock_ins table
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->date('date')->change();
        });

        // Revert back to date in stock_outs table
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->date('date')->change();
        });
    }
};

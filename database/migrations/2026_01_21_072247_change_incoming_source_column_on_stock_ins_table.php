<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->string('incoming_source', 255)->change();
        });
    }

    public function down(): void
    {
        // rollback aman (kalau mau balik)
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->string('incoming_source', 255)->change();
        });
    }
};

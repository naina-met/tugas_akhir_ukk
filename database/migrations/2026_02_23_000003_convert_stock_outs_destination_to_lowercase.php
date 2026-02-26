<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert existing values to lowercase
        DB::statement("UPDATE stock_outs SET outgoing_destination = LOWER(outgoing_destination)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a data migration, we won't revert data changes
    }
};

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
            // Add is_borrowed column to track borrowed items
            $table->boolean('is_borrowed')->default(false)->after('outgoing_destination')->comment('Is item borrowed or permanently out');
            
            // Add return_date for borrowed items
            $table->date('return_date')->nullable()->after('is_borrowed')->comment('Expected return date for borrowed items');
            
            // Add returned_at for tracking actual return
            $table->timestamp('returned_at')->nullable()->after('return_date')->comment('Actual return timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dropColumn(['is_borrowed', 'return_date', 'returned_at']);
        });
    }
};

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
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('outgoing_destination', ['Penjualan','Pemakaian internal','Rusak']); // Destination of stock out, e.g., customer name
            $table->text('description')->nullable(); // Additional details about the stock out
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User who performed the stock ou
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};

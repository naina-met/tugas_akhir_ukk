<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();

            // pelapor (users)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // barang (items) - opsional
            $table->foreignId('item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('location');
            $table->text('description');

            // foto
            $table->string('photo_report');
            $table->string('photo_result')->nullable();

            // status tiket
            $table->enum('status', ['pending', 'process', 'done'])
                ->default('pending');

            // catatan admin
            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};

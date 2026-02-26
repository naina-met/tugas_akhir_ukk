<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('peminjamans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang pinjam
        $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Barang apa
        $table->integer('jumlah');
        $table->date('tgl_pinjam');
        $table->date('tgl_kembali_max');
        $table->string('status')->default('pending'); // pending, dipinjam, ditolak
        $table->text('alasan_penolakan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};

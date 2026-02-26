<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    // Kolom yang boleh diisi
   protected $fillable = ['user_id', 'item_id', 'jumlah', 'tgl_pinjam', 'tgl_kembali_max', 'status', 'alasan_penolakan'];

public function item() {
    return $this->belongsTo(Item::class);
}

public function user() {
    return $this->belongsTo(User::class);
}
}
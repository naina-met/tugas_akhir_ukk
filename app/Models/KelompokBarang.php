<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokBarang extends Model
{
    use HasFactory;

    protected $table = 'kelompok_barang';

    protected $fillable = [
        'jenis_barang_id',
        'name'
    ];

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}

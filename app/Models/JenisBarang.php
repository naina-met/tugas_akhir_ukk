<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory;

    protected $table = 'jenis_barang';

    protected $fillable = [
        'name'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // Keep this for backwards compatibility if needed
    public function kelompokBarang()
    {
        return $this->hasMany(KelompokBarang::class);
    }
}

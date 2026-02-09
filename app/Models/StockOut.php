<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_outs';

    protected $fillable = [
        'date',
        'item_id',
        'quantity',
        'outgoing_destination',
        'description',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date', // cukup date, jangan datetime
    ];

    /**
     * Relasi ke item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

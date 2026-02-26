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
        'is_borrowed',
        'return_date',
        'description',
        'user_id',
        'returned_at',
        'borrower_name'
    ];

    protected $casts = [
        'date' => 'datetime',
        'return_date' => 'date',
        'returned_at' => 'datetime',
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

    /**
     * Check if this is a permanent stock out or borrowed
     */
    public function isPermanent()
    {
        return !$this->is_borrowed || ($this->is_borrowed && $this->returned_at !== null);
    }

    /**
     * Mark item as returned
     */
    public function markAsReturned()
    {
        $this->update(['returned_at' => now()]);
        
        // Restore stock when returned
        $item = $this->item;
        $item->stock += $this->quantity;
        $item->save();
    }
}

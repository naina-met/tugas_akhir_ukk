<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\Item;

class InventoryReport
{
    /**
     * Ambil stok langsung dari tabel items agar SINKRON dengan halaman Barang
     * Stok di tabel items adalah sumber kebenaran (source of truth)
     * 
     * @param int $itemId
     * @return int Stok terkini (minimal 0, tidak boleh minus)
     */
    public static function calculateFinalStock($itemId)
    {
        // Ambil langsung dari tabel items - ini adalah stok yang ditampilkan di halaman Barang
        $item = Item::find($itemId);
        
        if (!$item) {
            return 0;
        }

        // Stok dari tabel items - SINKRON dengan halaman Barang
        $stock = $item->stock ?? 0;

        // PENTING: Tidak boleh minus, kalau habis ya 0
        return max(0, (int) $stock);
    }

    /**
     * Hitung stok berdasarkan transaksi masuk/keluar (sebagai backup/validasi)
     * 
     * @param int $itemId
     * @return int Stok dari kalkulasi transaksi
     */
    public static function calculateStockFromTransactions($itemId)
    {
        // Total semua barang masuk untuk item ini
        $totalMasuk = DB::table('stock_ins')
            ->where('item_id', $itemId)
            ->sum('quantity');

        // Total semua barang keluar untuk item ini
        $totalKeluar = DB::table('stock_outs')
            ->where('item_id', $itemId)
            ->sum('quantity');

        // Hitung selisih
        $stock = $totalMasuk - $totalKeluar;

        return max(0, $stock);
    }

    /**
     * Hitung stok pada tanggal tertentu (untuk historical tracking)
     * 
     * @param int $itemId
     * @param string $date Format: Y-m-d H:i:s
     * @return int Stok pada tanggal tersebut (minimal 0)
     */
    public static function calculateStockAtDate($itemId, $date)
    {
        $totalMasuk = DB::table('stock_ins')
            ->where('item_id', $itemId)
            ->where('created_at', '<=', $date)
            ->sum('quantity');

        $totalKeluar = DB::table('stock_outs')
            ->where('item_id', $itemId)
            ->where('created_at', '<=', $date)
            ->sum('quantity');

        $stock = $totalMasuk - $totalKeluar;

        return max(0, $stock);
    }
}

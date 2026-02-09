<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\InventoryReport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        // Case insensitive check untuk role superadmin
        $isSuperAdmin = (strtolower($authUser->role) === 'superadmin');

        // --- 1. AMBIL DATA DASAR (UNION) ---
        // Gunakan date yang diinput user saat transaksi, bukan created_at
        $laporanMasuk = DB::table('stock_ins')
            ->join('items', 'stock_ins.item_id', '=', 'items.id')
            ->join('users', 'stock_ins.user_id', '=', 'users.id')
            ->select(
                'stock_ins.id',
                'stock_ins.date as tanggal',  // Gunakan date bukan created_at
                'stock_ins.created_at as created_timestamp', // Simpan created_at untuk sorting
                'items.id as item_id',
                'items.name as barang',
                DB::raw("'Barang Masuk' as jenis_transaksi"),
                'stock_ins.quantity as jumlah',
                DB::raw('NULL as tujuan'),
                DB::raw('NULL as keterangan'),
                'users.username as user',
                'users.id as user_id',
                'users.role as user_role',
                DB::raw("'IN' as tipe")
            );

        $laporanKeluar = DB::table('stock_outs')
            ->join('items', 'stock_outs.item_id', '=', 'items.id')
            ->join('users', 'stock_outs.user_id', '=', 'users.id')
            ->select(
                'stock_outs.id',
                'stock_outs.date as tanggal',  // Gunakan date bukan created_at
                'stock_outs.created_at as created_timestamp', // Simpan created_at untuk sorting
                'items.id as item_id',
                'items.name as barang',
                DB::raw("'Barang Keluar' as jenis_transaksi"),
                'stock_outs.quantity as jumlah',
                'stock_outs.outgoing_destination as tujuan',
                'stock_outs.description as keterangan',
                'users.username as user',
                'users.id as user_id',
                'users.role as user_role',
                DB::raw("'OUT' as tipe")
            );

        $query = DB::query()->fromSub($laporanMasuk->unionAll($laporanKeluar), 'laporan');

        // --- 2. LOGIKA FILTER AKSES (CORE LOGIC) ---
        
        // Jika BUKAN Superadmin (role admin), WAJIB filter ID diri sendiri
        // Admin TIDAK BISA melihat aktivitas user lain atau superadmin
        if (!$isSuperAdmin) {
            $query->where('user_id', $authUser->id);
        } 
        // Jika Superadmin, bisa melihat SEMUA aktivitas semua user
        else {
            // Filter berdasarkan user jika dropdown dipilih
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        // Filter Tambahan (Tanggal, Barang, Tipe)
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }
        if ($request->filled('jenis_transaksi')) {
            $query->where('tipe', $request->jenis_transaksi);
        }

        // Ambil data urut Ascending berdasarkan tanggal transaksi dan created_at untuk hitung saldo berjalan
        $reports = $query->orderBy('tanggal', 'asc')
                         ->orderBy('created_timestamp', 'asc')
                         ->get();

        // --- 3. HITUNG SISA STOK SEBAGAI RUNNING BALANCE ---
        // Kelompokkan per item dan hitung running balance
        $itemStocks = []; // Tracking stok per item
        
        // Pertama, ambil stok awal sebelum transaksi pertama yang ditampilkan
        // Dengan cara menghitung semua transaksi SEBELUM tanggal pertama yang ditampilkan
        foreach ($reports as $report) {
            $itemId = $report->item_id;
            
            if (!isset($itemStocks[$itemId])) {
                // Hitung stok pada saat transaksi ini terjadi
                // Stok = semua masuk sampai tanggal ini - semua keluar sampai tanggal ini
                $itemStocks[$itemId] = InventoryReport::calculateStockAtDate(
                    $itemId, 
                    $report->created_timestamp
                );
            } else {
                // Update running balance
                if ($report->tipe === 'IN') {
                    $itemStocks[$itemId] += $report->jumlah;
                } else {
                    $itemStocks[$itemId] -= $report->jumlah;
                }
            }
            
            // Set sisa_stock untuk report ini
            $report->sisa_stock = max(0, $itemStocks[$itemId]);
        }

        // Balikkan urutan ke Descending (terbaru di atas) untuk tampilan user
        $reports = $reports->sortByDesc('tanggal')
                          ->sortByDesc('created_timestamp')
                          ->values();

        // --- 4. DATA UNTUK VIEW ---
        // Untuk superadmin: tampilkan semua user untuk filter
        // Untuk admin: tidak perlu list user (hanya lihat aktivitas sendiri)
        $usersForFilter = $isSuperAdmin ? User::orderBy('username')->get() : collect([]);

        return view('reports.index', [
            'reports' => $reports,
            'items' => Item::orderBy('name')->get(),
            'users' => $usersForFilter,
            'filters' => $request->all(),
            'isSuperAdmin' => $isSuperAdmin,
            'currentUser' => $authUser
        ]);
    }
}

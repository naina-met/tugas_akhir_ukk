<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $isSuperAdmin = (strtolower($authUser->role) === 'superadmin');

      // --- 1. AMBIL DATA: BARANG MASUK ---
$laporanMasuk = DB::table('stock_ins')
    ->join('items', 'stock_ins.item_id', '=', 'items.id')
    ->join('users', 'stock_ins.user_id', '=', 'users.id')
    ->select(
        'stock_ins.id',
        'stock_ins.date as tanggal',
        'stock_ins.created_at as created_timestamp',
        'items.id as item_id',
        'items.name as barang',
        'items.condition as kondisi', // <--- Ambil dari master tabel items
        DB::raw("'Barang Masuk' as jenis_transaksi"),
        'stock_ins.quantity as jumlah',
        DB::raw('NULL as tujuan'),
        DB::raw('NULL as keterangan'),
        'users.username as user',
        'users.id as user_id',
        'users.role as user_role',
        DB::raw("'IN' as tipe")
    );

// --- 2. AMBIL DATA: BARANG KELUAR (PERMANEN & PINJAM) ---
$laporanKeluar = DB::table('stock_outs')
    ->join('items', 'stock_outs.item_id', '=', 'items.id')
    ->join('users', 'stock_outs.user_id', '=', 'users.id')
    ->select(
        'stock_outs.id',
        'stock_outs.date as tanggal',
        'stock_outs.created_at as created_timestamp',
        'items.id as item_id',
        'items.name as barang',
        'items.condition as kondisi', // <--- Ambil dari master tabel items
        DB::raw("IF(stock_outs.is_borrowed = 1, 'Dipinjam', 'Barang Keluar') as jenis_transaksi"),
        'stock_outs.quantity as jumlah',
        'stock_outs.outgoing_destination as tujuan',
        'stock_outs.description as keterangan',
        'users.username as user',
        'users.id as user_id',
        'users.role as user_role',
        DB::raw("'OUT' as tipe")
    );

// --- 3. AMBIL DATA: BARANG DIKEMBALIKAN ---
$laporanPengembalian = DB::table('stock_outs')
    ->join('items', 'stock_outs.item_id', '=', 'items.id')
    ->join('users', 'stock_outs.user_id', '=', 'users.id')
    ->where('stock_outs.is_borrowed', 1)
    ->whereNotNull('stock_outs.returned_at') 
    ->select(
        'stock_outs.id',
        DB::raw("DATE(stock_outs.returned_at) as tanggal"), 
        'stock_outs.returned_at as created_timestamp',
        'items.id as item_id',
        'items.name as barang',
        'items.condition as kondisi', // <--- Ambil dari master tabel items
        DB::raw("'Dikembalikan' as jenis_transaksi"),
        'stock_outs.quantity as jumlah',
        DB::raw('NULL as tujuan'),
        DB::raw("CONCAT('Kembali dari: ', stock_outs.borrower_name, ' (Ket: ', IFNULL(stock_outs.description, '-'), ')') as keterangan"),
        'users.username as user',
        'users.id as user_id',
        'users.role as user_role',
        DB::raw("'IN' as tipe") 
    );
        // GABUNGKAN KETIGA TRANSAKSI
        $query = DB::query()->fromSub(
            $laporanMasuk->unionAll($laporanKeluar)->unionAll($laporanPengembalian), 
            'laporan'
        );

        // HANYA Filter item_id di level Database (agar proses perhitungan tidak berat)
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        // --- 4. ALGORITMA RUNNING BALANCE (SISA STOK) ---
        // WAJIB ditarik SEMUA tanpa mempedulikan user/tanggal agar matematikanya akurat dari 0
        $allReports = $query->orderBy('tanggal', 'asc')
                            ->orderBy('created_timestamp', 'asc')
                            ->get();

        $itemStocks = []; 
        $processedReports = collect();

        foreach ($allReports as $report) {
            $itemId = $report->item_id;
            
            if (!isset($itemStocks[$itemId])) {
                $itemStocks[$itemId] = 0; 
            }

            // Hitung +/- berdasarkan Tipe
            if ($report->tipe === 'IN') {
                $itemStocks[$itemId] += $report->jumlah; 
            } else {
                $itemStocks[$itemId] -= $report->jumlah; 
            }

            // Simpan sisa stok saat transaksi ini terjadi
            $report->sisa_stock = $itemStocks[$itemId];
            $processedReports->push($report);
        }

        // --- 5. FILTER TAMPILAN (SETELAH MATEMATIKA SELESAI) ---
        
        // A. Filter Akses Admin (Sembunyikan data milik orang lain)
        if (!$isSuperAdmin) {
            $processedReports = $processedReports->where('user_id', $authUser->id);
        } else {
            if ($request->filled('user_id')) {
                $processedReports = $processedReports->where('user_id', $request->user_id);
            }
        }

        // B. Filter Tanggal & Jenis Transaksi
        if ($request->filled('tanggal_dari')) {
            $processedReports = $processedReports->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $processedReports = $processedReports->where('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('jenis_transaksi')) {
            $processedReports = $processedReports->where('tipe', $request->jenis_transaksi);
        }

        // --- 6. BALIKKAN URUTAN & LEMPAR KE VIEW ---
        // Urutkan menurun agar transaksi terbaru ada di paling atas tabel
        $reports = $processedReports->sortByDesc('created_timestamp')
                                    ->sortByDesc('tanggal')
                                    ->values();

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
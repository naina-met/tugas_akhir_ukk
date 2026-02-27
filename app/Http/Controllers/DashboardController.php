<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Peminjaman;
use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index()
{
    $user = auth()->user();
    $items = \App\Models\Item::all();

    if (strtolower($user->role) === 'user') {
        $myLoans = Peminjaman::where('user_id', $user->id)->latest()->get();
        // User tidak butuh $pendingLoans
        return view('user.dashboard', compact('myLoans', 'items'));
    } else {
       // PERBAIKAN DI SINI: Tambahkan 'menunggu_kembali' agar terbaca oleh notifikasi
        $pendingLoans = Peminjaman::whereIn('status', ['pending', 'menunggu_kembali'])
                            ->with(['user', 'item'])
                            ->latest()
                            ->get();

        return view('dashboard', compact('pendingLoans', 'items'));
    }
}

    public function approve($id)
{
    DB::transaction(function () use ($id) {
        $loan = Peminjaman::findOrFail($id);
        if ($loan->status == 'pending') {
            $loan->update(['status' => 'disetujui']);
            
            // SINKRONISASI: Mengurangi stok item
            $loan->item->decrement('stock', $loan->jumlah);
        }
    });
    return back()->with('success', 'Disetujui, stok barang otomatis berkurang!');
}
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);

        $loan = Peminjaman::where('id', $id)->where('status', 'pending')->firstOrFail();

        $loan->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Peminjaman telah ditolak.');
    }



    private function getDashboardData()
    {
        /* =========================
         * STATISTIK UTAMA
         * ========================= */
        $totalItems = Item::count();
        $totalCategories = Category::count();
        $lowStock = Item::where('stock', '<=', 10)
                        ->where('stock', '>', 0)
                        ->count();
        $outOfStock = Item::where('stock', '<=', 0)->count();

        /* =========================
         * GRAFIK 7 HARI TERAKHIR
         * TERMASUK HARI INI
         * ========================= */
        $startDate = Carbon::today()->subDays(6); // 6 hari lalu
        $endDate   = Carbon::today();             // hari ini

        $chartLabels = [];
        for ($i = 0; $i < 7; $i++) {
            $chartLabels[] = $startDate->copy()
                ->addDays($i)
                ->format('d M');
        }

        // --- Stock In (group by tanggal)
        $stockInRaw = StockIn::whereBetween('date', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay()
            ])
            ->selectRaw('DATE(date) as tanggal, SUM(quantity) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $stockInData = [];
        for ($i = 0; $i < 7; $i++) {
            $tgl = $startDate->copy()->addDays($i)->toDateString();
            $stockInData[] = (int) ($stockInRaw[$tgl] ?? 0);
        }

        // --- Stock Out (group by tanggal)
        $stockOutRaw = StockOut::whereBetween('date', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay()
            ])
            ->selectRaw('DATE(date) as tanggal, SUM(quantity) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $stockOutData = [];
        for ($i = 0; $i < 7; $i++) {
            $tgl = $startDate->copy()->addDays($i)->toDateString();
            $stockOutData[] = (int) ($stockOutRaw[$tgl] ?? 0);
        }

        /* =========================
         * DISTRIBUSI STOK PER KATEGORI
         * ========================= */
        $categories = Category::withCount('items')->get();
        $categoryLabels = $categories->pluck('name')->toArray();
        $categoryData = [];

        foreach ($categories as $category) {
            $categoryData[] = Item::where('category_id', $category->id)->sum('stock');
        }

        /* =========================
         * BARANG MASUK TERBARU
         * ========================= */
        $recentStockIn = StockIn::with('item')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($stockIn) {
                $date = $stockIn->date
                    ? Carbon::parse($stockIn->date)
                    : $stockIn->created_at;

                return [
                    'item_name' => $stockIn->item->name ?? 'Unknown',
                    'quantity'  => $stockIn->quantity,
                    'date'      => $date->format('Y-m-d'),
                    'time'      => $stockIn->created_at->format('H:i'),
                ];
            });

        /* =========================
         * BARANG KELUAR TERBARU
         * ========================= */
        $recentStockOut = StockOut::with('item')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($stockOut) {
                $date = $stockOut->date
                    ? Carbon::parse($stockOut->date)
                    : $stockOut->created_at;

                return [
                    'item_name' => $stockOut->item->name ?? 'Unknown',
                    'quantity'  => $stockOut->quantity,
                    'date'      => $date->format('Y-m-d'),
                    'time'      => $stockOut->created_at->format('H:i'),
                ];
            });

        /* =========================
         * RETURN DATA KE VIEW / AJAX
         * ========================= */
        return [
            'totalItems'      => $totalItems,
            'totalCategories' => $totalCategories,
            'lowStock'        => $lowStock,
            'outOfStock'      => $outOfStock,
            'chartLabels'     => $chartLabels,
            'stockInData'     => $stockInData,
            'stockOutData'    => $stockOutData,
            'categoryLabels'  => $categoryLabels,
            'categoryData'    => $categoryData,
            'recentStockIn'   => $recentStockIn,
            'recentStockOut'  => $recentStockOut,
        ];
    }

 
public function accKembali($id) // Pastikan namanya persis accKembali
{
    DB::transaction(function () use ($id) {
        $loan = Peminjaman::findOrFail($id);
        if ($loan->status == 'menunggu_kembali') {
            $loan->update(['status' => 'selesai']);
            $loan->item->increment('stock', $loan->jumlah);
        }
    });
    return back()->with('success', 'Barang diterima, stok kembali bertambah!');
}
}
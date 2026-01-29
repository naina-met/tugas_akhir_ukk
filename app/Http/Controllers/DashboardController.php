<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getDashboardData();

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json($data);
        }

        // Jika request biasa, return view
        return view('dashboard', $data);
    }

    private function getDashboardData()
    {
        // Stats
        $totalItems = Item::count();
        $totalCategories = Category::count();
        $lowStock = Item::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $outOfStock = Item::where('stock', '<=', 0)->count();

        // Tampilkan 7 hari terakhir (dari 6 hari yang lalu hingga hari ini)
        $days = [];
        $stockInData = [];
        $stockOutData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateFormatted = $date->format('d M');
            $days[] = $dateFormatted;

            // Query berdasarkan field 'date' yang diinput user
            $stockInSum = StockIn::whereDate('date', $date->format('Y-m-d'))->sum('quantity');
            $stockOutSum = StockOut::whereDate('date', $date->format('Y-m-d'))->sum('quantity');
            
            $stockInData[] = (int)$stockInSum;
            $stockOutData[] = (int)$stockOutSum;
        }

        // Stock Distribution by Category
        $categories = Category::withCount('items')->get();
        $categoryLabels = $categories->pluck('name')->toArray();
        $categoryData = [];
        
        foreach ($categories as $category) {
            $categoryData[] = Item::where('category_id', $category->id)->sum('stock');
        }

        // Recent Stock In - gunakan field 'date' untuk sorting dan display
        $recentStockIn = StockIn::with('item')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($stockIn) {
                $date = $stockIn->date ? Carbon::parse($stockIn->date) : $stockIn->created_at;
                return [
                    'item_name' => $stockIn->item->name ?? 'Unknown',
                    'quantity' => $stockIn->quantity,
                    'date' => $date->format('d M Y'),
                    'time' => $stockIn->created_at->format('H:i'),
                ];
            });

        // Recent Stock Out - gunakan field 'date' untuk sorting dan display
        $recentStockOut = StockOut::with('item')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($stockOut) {
                $date = $stockOut->date ? Carbon::parse($stockOut->date) : $stockOut->created_at;
                return [
                    'item_name' => $stockOut->item->name ?? 'Unknown',
                    'quantity' => $stockOut->quantity,
                    'date' => $date->format('d M Y'),
                    'time' => $stockOut->created_at->format('H:i'),
                ];
            });

        return [
            'totalItems' => $totalItems,
            'totalCategories' => $totalCategories,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'chartLabels' => $days,
            'stockInData' => $stockInData,
            'stockOutData' => $stockOutData,
            'categoryLabels' => $categoryLabels,
            'categoryData' => $categoryData,
            'recentStockIn' => $recentStockIn,
            'recentStockOut' => $recentStockOut,
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter bulan & tahun (YYYY-MM)
        $selected = $request->month_year ?? now()->format('Y-m');

        $carbon = Carbon::createFromFormat('Y-m', $selected);
        $month  = $carbon->month;
        $year   = $carbon->year;

        $daysInMonth = $carbon->daysInMonth;
        $labels = range(1, $daysInMonth);

        $stockInData  = [];
        $stockOutData = [];

        foreach ($labels as $day) {
            // BARANG MASUK
            $stockInData[] = StockIn::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->whereDay('date', $day)
                ->sum('quantity');

            // BARANG KELUAR
            $stockOutData[] = StockOut::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->whereDay('date', $day)
                ->sum('quantity');
        }

        // Hitung total untuk stat cards
        $totalStockIn = array_sum($stockInData);
        $totalStockOut = array_sum($stockOutData);

        return view('dashboard-rekap', compact(
            'labels',
            'stockInData',
            'stockOutData',
            'selected',
            'totalStockIn',
            'totalStockOut'
        ));
    }
}

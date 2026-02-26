<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportController extends Controller
{
    private function styleSheet($sheet, $lastColumn, $lastRow, $customWidths = [])
    {
        // Style untuk judul besar (baris 1)
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->getStyle("A1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1F2937']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Style untuk header tabel (baris 2) - biru dengan teks putih
        $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'B4C6E7'],
                ],
            ],
        ]);

        // Style untuk data (mulai baris 3) dengan zebra striping
        if ($lastRow >= 3) {
            $sheet->getStyle("A3:{$lastColumn}{$lastRow}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D6DCE4'],
                    ],
                ],
                'font' => ['size' => 10],
            ]);

            // Zebra striping (baris selang-seling warna biru muda)
            for ($r = 3; $r <= $lastRow; $r++) {
                if ($r % 2 == 1) {
                    $sheet->getStyle("A{$r}:{$lastColumn}{$r}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F2F6FC'],
                        ],
                    ]);
                }
            }

            // Set tinggi baris data
            for ($r = 3; $r <= $lastRow; $r++) {
                $sheet->getRowDimension($r)->setRowHeight(22);
            }
        }

        // Set lebar kolom secara dinamis untuk SEMUA kolom dari A sampai lastColumn
        $defaultWidth = 20;
        for ($col = 'A'; $col <= $lastColumn; $col++) {
            $width = $customWidths[$col] ?? $defaultWidth;
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Tinggi baris header
        $sheet->getRowDimension(2)->setRowHeight(28);

        // Freeze pane agar header tetap terlihat saat scroll
        $sheet->freezePane('A3');
    }

    private function downloadSheet(Spreadsheet $spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path("app/public/{$filename}");
        $writer->save($filePath);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function exportItems()
    {
        $items = Item::with('category', 'user')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ITEMS LIST');
        $sheet->fromArray(['Name', 'Code', 'Category', 'Stock', 'Condition', 'Unit', 'Created By'], null, 'A2');

        $row = 3;
        foreach ($items as $item) {
            $sheet->setCellValue("A{$row}", $item->name);
            $sheet->setCellValue("B{$row}", $item->code);
            $sheet->setCellValue("C{$row}", $item->category->name);
            $sheet->setCellValue("D{$row}", $item->stock);
            $sheet->setCellValue("E{$row}", $this->getConditionLabel($item->condition));
            $sheet->setCellValue("F{$row}", $item->unit);
            $sheet->setCellValue("G{$row}", $item->user->username ?? '-');
            $row++;
        }

        $this->styleSheet($sheet, 'G', $row - 1, [
            'A' => 25,
            'B' => 18,
            'C' => 22,
            'D' => 12,
            'E' => 18,
            'F' => 12,
            'G' => 18,
        ]);
        return $this->downloadSheet($spreadsheet, 'items.xlsx');
    }

    public function exportStockIns()
    {
        $stockIns = StockIn::with('item', 'user')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'STOCK IN LIST');
        $sheet->fromArray(['Date', 'Item', 'Quantity', 'Source', 'User'], null, 'A2');

        $row = 3;
        foreach ($stockIns as $stockIn) {
            $sheet->setCellValue("A{$row}", $stockIn->date);
            $sheet->setCellValue("B{$row}", $stockIn->item->name);
            $sheet->setCellValue("C{$row}", $stockIn->quantity);
            $sheet->setCellValue("D{$row}", $stockIn->incoming_source);
            $sheet->setCellValue("E{$row}", $stockIn->user->username);
            $row++;
        }

        $this->styleSheet($sheet, 'E', $row - 1, [
            'A' => 18,
            'B' => 28,
            'C' => 14,
            'D' => 25,
            'E' => 18,
        ]);
        return $this->downloadSheet($spreadsheet, 'stock_ins.xlsx');
    }

    public function exportStockOuts()
    {
        $stockOuts = StockOut::with('item', 'user')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'STOCK OUT LIST');
        $sheet->fromArray(['Date', 'Item', 'Quantity', 'Destination', 'Borrower', 'Type', 'Return Date', 'Status', 'User'], null, 'A2');

        $row = 3;
        foreach ($stockOuts as $stockOut) {
            $status = $stockOut->is_borrowed ? ($stockOut->returned_at ? 'Returned' : 'On Loan') : 'Permanent';

            $sheet->setCellValue("A{$row}", $stockOut->date);
            $sheet->setCellValue("B{$row}", $stockOut->item->name);
            $sheet->setCellValue("C{$row}", $stockOut->quantity);
            $sheet->setCellValue("D{$row}", $stockOut->outgoing_destination);
            $sheet->setCellValue("E{$row}", $stockOut->borrower_name ?? '-');
            $sheet->setCellValue("F{$row}", $stockOut->is_borrowed ? 'Borrowed' : 'Permanent');
            $sheet->setCellValue("G{$row}", $stockOut->return_date ? $stockOut->return_date->format('Y-m-d') : '-');
            $sheet->setCellValue("H{$row}", $status);
            $sheet->setCellValue("I{$row}", $stockOut->user->username);
            $row++;
        }

        $this->styleSheet($sheet, 'I', $row - 1, [
            'A' => 18,
            'B' => 25,
            'C' => 14,
            'D' => 25,
            'E' => 22,
            'F' => 16,
            'G' => 18,
            'H' => 16,
            'I' => 18,
        ]);
        return $this->downloadSheet($spreadsheet, 'stock_outs.xlsx');
    }

    public function exportDamageReports()
    {
        $reports = DamageReport::with('item', 'user')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'DAMAGE REPORTS LIST');
        $sheet->fromArray(['User', 'Item', 'Condition', 'Description', 'Status'], null, 'A2');

        $row = 3;
        foreach ($reports as $report) {
            $itemName = $report->item ? $report->item->name : '-';
            $sheet->setCellValue("A{$row}", $report->user->username);
            $sheet->setCellValue("B{$row}", $itemName);
            $sheet->setCellValue("C{$row}", $this->getConditionLabel($report->condition));
            $sheet->setCellValue("D{$row}", $report->description);
            $sheet->setCellValue("E{$row}", ucfirst($report->status));
            $row++;
        }

        $this->styleSheet($sheet, 'E', $row - 1, [
            'A' => 20,
            'B' => 28,
            'C' => 18,
            'D' => 35,
            'E' => 16,
        ]);
        return $this->downloadSheet($spreadsheet, 'damage_reports.xlsx');
    }

    public function exportReports(Request $request)
    {
        $stockIn = DB::table('stock_ins')
            ->join('items', 'stock_ins.item_id', '=', 'items.id')
            ->join('users', 'stock_ins.user_id', '=', 'users.id')
            ->select('stock_ins.date as tanggal', 'items.name as barang', 'items.condition as kondisi', DB::raw("'IN' as tipe"), 'stock_ins.quantity as jumlah', 'stock_ins.incoming_source as tujuan', 'users.username as user');

        $stockOut = DB::table('stock_outs')
            ->join('items', 'stock_outs.item_id', '=', 'items.id')
            ->join('users', 'stock_outs.user_id', '=', 'users.id')
            ->select('stock_outs.date as tanggal', 'items.name as barang', 'items.condition as kondisi', DB::raw("'OUT' as tipe"), 'stock_outs.quantity as jumlah', 'stock_outs.outgoing_destination as tujuan', 'users.username as user');

        $reports = $stockIn->unionAll($stockOut)->orderBy('tanggal', 'asc')->get();

        $runningBalance = 0;
        foreach ($reports as $r) {
            if ($r->tipe == 'IN') {
                $runningBalance += $r->jumlah;
            } else {
                $runningBalance -= $r->jumlah;
            }
            $r->sisa_stock = $runningBalance;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAPORAN AKTIVITAS STOK');
        $sheet->fromArray(['No', 'Tanggal', 'Barang', 'Kondisi', 'Tipe', 'Jumlah', 'Sumber/Tujuan', 'User', 'Sisa Stok'], null, 'A2');

        $row = 3;
        foreach ($reports as $index => $r) {
            $sheet->setCellValue("A{$row}", $index + 1);
            $sheet->setCellValue("B{$row}", $r->tanggal);
            $sheet->setCellValue("C{$row}", $r->barang);
            $sheet->setCellValue("D{$row}", $r->kondisi ?? '-');
            $sheet->setCellValue("E{$row}", $r->tipe == 'IN' ? 'Masuk' : 'Keluar');
            $sheet->setCellValue("F{$row}", $r->jumlah);
            $sheet->setCellValue("G{$row}", $r->tujuan ?? '-');
            $sheet->setCellValue("H{$row}", $r->user);
            $sheet->setCellValue("I{$row}", $r->sisa_stock);
            $row++;
        }

        $this->styleSheet($sheet, 'I', $row - 1, [
            'A' => 8,
            'B' => 18,
            'C' => 25,
            'D' => 18,
            'E' => 14,
            'F' => 12,
            'G' => 25,
            'H' => 18,
            'I' => 14,
        ]);
        return $this->downloadSheet($spreadsheet, 'laporan_aktivitas.xlsx');
    }

    private function getConditionLabel($condition)
    {
        $labels = [
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
        ];
        return $labels[$condition] ?? $condition;
    }
}
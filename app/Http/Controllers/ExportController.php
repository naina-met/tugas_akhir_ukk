<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\DamageReport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportController extends Controller
{
    private function styleSheet($sheet, $lastColumn, $lastRow)
    {
        // Style untuk judul besar (baris 1)
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->getStyle("A1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Style untuk header tabel (baris 2)
        $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f3f3f3'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Style untuk data (mulai baris 3)
        $sheet->getStyle("A3:{$lastColumn}{$lastRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Set lebar kolom manual agar tidak sempit
        $columnWidths = [
            'A' => 20,
            'B' => 25,
            'C' => 25,
            'D' => 20,
            'E' => 20,
        ];

        foreach ($columnWidths as $col => $width) {
            if (ord($col) <= ord($lastColumn)) {
                $sheet->getColumnDimension($col)->setWidth($width);
            }
        }

        // Tinggi baris header
        $sheet->getRowDimension(2)->setRowHeight(25);
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

        // Judul
        $sheet->setCellValue('A1', 'ITEMS LIST');

        // Header tabel
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

        $this->styleSheet($sheet, 'G', $row - 1);
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

        $this->styleSheet($sheet, 'E', $row - 1);
        return $this->downloadSheet($spreadsheet, 'stock_ins.xlsx');
    }

    public function exportStockOuts()
    {
        $stockOuts = StockOut::with('item', 'user')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'STOCK OUT LIST');
        $sheet->fromArray(['Date', 'Item', 'Quantity', 'Destination', 'Type', 'Return Date', 'Status', 'User'], null, 'A2');

        $row = 3;
        foreach ($stockOuts as $stockOut) {
            $status = $stockOut->is_borrowed ? ($stockOut->returned_at ? 'Returned' : 'On Loan') : 'Permanent';
            $sheet->setCellValue("A{$row}", $stockOut->date);
            $sheet->setCellValue("B{$row}", $stockOut->item->name);
            $sheet->setCellValue("C{$row}", $stockOut->quantity);
            $sheet->setCellValue("D{$row}", $stockOut->outgoing_destination);
            $sheet->setCellValue("E{$row}", $stockOut->is_borrowed ? 'Borrowed' : 'Permanent');
            $sheet->setCellValue("F{$row}", $stockOut->return_date ? $stockOut->return_date->format('Y-m-d') : '-');
            $sheet->setCellValue("G{$row}", $status);
            $sheet->setCellValue("H{$row}", $stockOut->user->username);
            $row++;
        }

        $this->styleSheet($sheet, 'H', $row - 1);
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

        $this->styleSheet($sheet, 'E', $row - 1);
        return $this->downloadSheet($spreadsheet, 'damage_reports.xlsx');
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

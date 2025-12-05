<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DaftarIsiBerkasTemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    public function __construct()
    {
    }

    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE KLASIFIKASI / NOMOR BERKAS',
            'INDEKS',
            'NAMA BERKAS',
            'TANGGAL BUAT BERKAS',
            'NO ITEM ARSIP',
            'URAIAN INFORMASI',
            'TANGGAL',
            'JUMLAH',
            'LOKASI BERKAS',
            'Ruang',
            'No Rak',
            'No Laci',
            'No Box',
            'No Folder',
            'KETERANGAN',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 15,
            'D' => 25,
            'E' => 12,
            'F' => 8,
            'G' => 40,
            'H' => 12,
            'I' => 8,
            'J' => 20,
            'K' => 10,
            'L' => 10,
            'M' => 10,
            'N' => 10,
            'O' => 10,
            'P' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Insert 1 row for sub-header (header sudah di row 1)
                $sheet->insertNewRowBefore(1, 1);
                
                // Row 1: Main headers
                $mainHeaders = ['NO', 'KODE KLASIFIKASI / NOMOR BERKAS', 'INDEKS', 'NAMA BERKAS', 'TANGGAL BUAT BERKAS', 
                               'NO ITEM ARSIP', 'URAIAN INFORMASI', 'TANGGAL', 'JUMLAH', 'LOKASI BERKAS'];
                foreach ($mainHeaders as $colIndex => $heading) {
                    $col = chr(65 + $colIndex);
                    $sheet->mergeCells($col . '1:' . $col . '2');
                    $sheet->setCellValue($col . '1', $heading);
                }
                
                // Merge K1:O1 for "Lokasi Arsip" header
                $sheet->mergeCells('K1:O1');
                $sheet->setCellValue('K1', 'Lokasi Arsip');
                
                // Row 2: Sub-headers for Lokasi Arsip
                $lokasiHeaders = ['Ruang', 'No Rak', 'No Laci', 'No Box', 'No Folder'];
                $startCol = 10;
                foreach ($lokasiHeaders as $idx => $heading) {
                    $col = chr(65 + $startCol + $idx);
                    $sheet->setCellValue($col . '2', $heading);
                }
                
                // Keterangan column (P) - merge vertically
                $sheet->mergeCells('P1:P2');
                $sheet->setCellValue('P1', 'KETERANGAN');

                // Header row style (row 1-2)
                $sheet->getStyle('A1:P2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 9],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'f2f2f2'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(25);
            },
        ];
    }
}

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

class ArsipUnitTemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
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
            'No',
            'Kode Klasifikasi',
            'Indeks',
            'Uraian Informasi',
            'Tanggal',
            'Jumlah',
            'Tingkat Perkembangan',
            'Unit Pengolah',
            'Retensi Aktif',
            'Retensi Inaktif',
            'SKKAAD',
            'Ruang',
            'No Rak',
            'No Laci',
            'No Box',
            'No Folder',
            'Keterangan'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 15,
            'D' => 40,
            'E' => 12,
            'F' => 15,
            'G' => 20,
            'H' => 20,
            'I' => 12,
            'J' => 14,
            'K' => 12,
            'L' => 10,
            'M' => 10,
            'N' => 10,
            'O' => 10,
            'P' => 10,
            'Q' => 20,
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
                $mainHeaders = ['No', 'Kode Klasifikasi', 'Indeks', 'Uraian Informasi', 'Tanggal', 'Jumlah', 
                               'Tingkat Perkembangan', 'Unit Pengolah', 'Retensi Aktif', 'Retensi Inaktif', 'SKKAAD'];
                foreach ($mainHeaders as $colIndex => $heading) {
                    $col = chr(65 + $colIndex);
                    $sheet->mergeCells($col . '1:' . $col . '2');
                    $sheet->setCellValue($col . '1', $heading);
                }
                
                // Merge L1:P1 for "Lokasi Fisik" header
                $sheet->mergeCells('L1:P1');
                $sheet->setCellValue('L1', 'Lokasi Fisik');
                
                // Row 2: Sub-headers for Lokasi Fisik
                $lokasiHeaders = ['Ruang', 'No Rak', 'No Laci', 'No Box', 'No Folder'];
                $startCol = 11;
                foreach ($lokasiHeaders as $idx => $heading) {
                    $col = chr(65 + $startCol + $idx);
                    $sheet->setCellValue($col . '2', $heading);
                }
                
                // Keterangan column (Q) - merge vertically
                $sheet->mergeCells('Q1:Q2');
                $sheet->setCellValue('Q1', 'Keterangan');

                // Header row style (row 1-2)
                $sheet->getStyle('A1:Q2')->applyFromArray([
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

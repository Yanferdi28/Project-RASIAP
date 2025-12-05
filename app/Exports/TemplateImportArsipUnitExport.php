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

class TemplateImportArsipUnitExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return [
            'Kode Klasifikasi *',
            'Indeks *',
            'Uraian Informasi *',
            'Tanggal *',
            'Jumlah',
            'Satuan',
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
            'Keterangan',
            'Kategori',
            'Sub Kategori',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // Kode Klasifikasi
            'B' => 15,  // Indeks
            'C' => 40,  // Uraian Informasi
            'D' => 12,  // Tanggal
            'E' => 10,  // Jumlah
            'F' => 10,  // Satuan
            'G' => 18,  // Tingkat Perkembangan
            'H' => 20,  // Unit Pengolah
            'I' => 12,  // Retensi Aktif
            'J' => 12,  // Retensi Inaktif
            'K' => 12,  // SKKAAD
            'L' => 12,  // Ruang
            'M' => 10,  // No Rak
            'N' => 10,  // No Laci
            'O' => 10,  // No Box
            'P' => 10,  // No Folder
            'Q' => 25,  // Keterangan
            'R' => 15,  // Kategori
            'S' => 15,  // Sub Kategori
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
                
                // Header row style
                $sheet->getStyle('A1:S1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10],
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
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Add note row
                $sheet->setCellValue('A2', 'Catatan: Kolom dengan tanda * wajib diisi. Retensi Aktif, Retensi Inaktif, dan SKKAAD akan otomatis terisi dari Kode Klasifikasi jika dikosongkan.');
                $sheet->mergeCells('A2:S2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '666666']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
            },
        ];
    }
}

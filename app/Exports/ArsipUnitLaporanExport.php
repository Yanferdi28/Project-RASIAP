<?php

namespace App\Exports;

use App\Models\ArsipUnit;
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

class ArsipUnitLaporanExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    protected $records;
    protected $unitPengolah;
    protected $periode;

    public function __construct($records, $unitPengolah = null, $periode = null)
    {
        $this->records = $records;
        
        // Ambil unit pengolah dari user yang login jika tidak disediakan
        if ($unitPengolah) {
            $this->unitPengolah = $unitPengolah;
        } else {
            $user = auth()->user();
            $this->unitPengolah = $user->unitPengolah->nama_unit ?? 'Unit Pengolah';
        }
        
        $this->periode = $periode ?? now()->format('d F Y');
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->records as $index => $record) {
            $rows[] = [
                'no' => $index + 1,
                'kode_klasifikasi' => $record->kodeKlasifikasi->kode_klasifikasi ?? 'N/A',
                'indeks' => $record->indeks ?? '',
                'uraian_informasi' => $record->uraian_informasi ?? '',
                'tanggal' => $record->tanggal ? $record->tanggal->format('d-m-Y') : '',
                'jumlah_nilai' => $record->jumlah_nilai . ' ' . ($record->jumlah_satuan ?? ''),
                'tingkat_perkembangan' => $record->tingkat_perkembangan ?? '',
                'unit_pengolah' => $record->unitPengolah->nama_unit ?? 'N/A',
                'retensi_aktif' => $record->retensi_aktif ?? 0,
                'retensi_inaktif' => $record->retensi_inaktif ?? 0,
                'skkaad' => $record->skkaad ?? '',
                'ruang' => $record->ruangan ?? '-',
                'no_rak' => $record->no_filling ?? '-',
                'no_laci' => $record->no_laci ?? '-',
                'no_box' => $record->no_box ?? '-',
                'no_folder' => $record->no_folder ?? '-',
                'keterangan' => $record->keterangan ?? '',
            ];
        }

        return $rows;
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
            'A' => 5,   // No
            'B' => 15,  // Kode Klasifikasi
            'C' => 15,  // Indeks
            'D' => 40,  // Uraian Informasi
            'E' => 12,  // Tanggal
            'F' => 15,  // Jumlah
            'G' => 20,  // Tingkat Perkembangan
            'H' => 20,  // Unit Pengolah
            'I' => 12,  // Retensi Aktif
            'J' => 14,  // Retensi Inaktif
            'K' => 12,  // SKKAAD
            'L' => 10,  // Ruang
            'M' => 10,  // No Rak
            'N' => 10,  // No Laci
            'O' => 10,  // No Box
            'P' => 10,  // No Folder
            'Q' => 20,  // Keterangan
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
                
                // Insert 5 rows at the top for title (extra row for sub-header)
                $sheet->insertNewRowBefore(1, 5);
                
                // Set title in row 1
                $sheet->mergeCells('A1:Q1');
                $sheet->setCellValue('A1', 'LAPORAN DAFTAR ARSIP UNIT');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Set unit pengolah in row 2
                $sheet->mergeCells('A2:Q2');
                $sheet->setCellValue('A2', 'UNIT PENGOLAH: ' . $this->unitPengolah);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Set periode in row 3
                $sheet->mergeCells('A3:Q3');
                $sheet->setCellValue('A3', 'PERIODE: ' . $this->periode);
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Row 5: Main headers (merge columns A-K vertically, L-P for "Lokasi Fisik", Q for Keterangan)
                $mainHeaders = ['No', 'Kode Klasifikasi', 'Indeks', 'Uraian Informasi', 'Tanggal', 'Jumlah', 
                               'Tingkat Perkembangan', 'Unit Pengolah', 'Retensi Aktif', 'Retensi Inaktif', 'SKKAAD'];
                foreach ($mainHeaders as $colIndex => $heading) {
                    $col = chr(65 + $colIndex);
                    $sheet->mergeCells($col . '5:' . $col . '6');
                    $sheet->setCellValue($col . '5', $heading);
                }
                
                // Merge L5:P5 for "Lokasi Fisik" header
                $sheet->mergeCells('L5:P5');
                $sheet->setCellValue('L5', 'Lokasi Fisik');
                
                // Row 6: Sub-headers for Lokasi Fisik
                $lokasiHeaders = ['Ruang', 'No Rak', 'No Laci', 'No Box', 'No Folder'];
                $startCol = 11; // L = 11 (0-indexed)
                foreach ($lokasiHeaders as $idx => $heading) {
                    $col = chr(65 + $startCol + $idx);
                    $sheet->setCellValue($col . '6', $heading);
                }
                
                // Keterangan column (Q) - merge vertically
                $sheet->mergeCells('Q5:Q6');
                $sheet->setCellValue('Q5', 'Keterangan');

                // Header row style (row 5-6)
                $sheet->getStyle('A5:Q6')->applyFromArray([
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
                ]);
                $sheet->getRowDimension(5)->setRowHeight(25);
                $sheet->getRowDimension(6)->setRowHeight(25);

                $highestRow = $sheet->getHighestRow();
                $highestColumn = 'Q';

                // Apply borders to data area
                $sheet->getStyle('A5:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Center align ALL columns
                $sheet->getStyle('A5:' . $highestColumn . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A5:' . $highestColumn . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Wrap text for the 'Uraian Informasi' and 'Keterangan' columns
                $sheet->getStyle('D7:D' . $highestRow)->getAlignment()->setWrapText(true);
                $sheet->getStyle('Q7:Q' . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
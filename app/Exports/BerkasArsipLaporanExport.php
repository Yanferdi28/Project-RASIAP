<?php

namespace App\Exports;

use App\Models\BerkasArsip;
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

class BerkasArsipLaporanExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
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
                'kode_klasifikasi' => $record->klasifikasi->kode_klasifikasi ?? 'N/A',
                'nama_berkas' => $record->nama_berkas,
                'tanggal_buat_berkas' => $record->created_at->format('d-m-Y'),
                'kurun_waktu' => $record->created_at->format('d M Y') . ' s/d ' . $record->updated_at->format('d M Y'),
                'jumlah_item' => 1, // Default to 1 as in the original
                'retensi_aktif' => $record->retensi_aktif ?? 0, // Ensure it shows 0 if null
                'retensi_inaktif' => $record->retensi_inaktif ?? 0, // Ensure it shows 0 if null
                'status_akhir' => $record->penyusutan_akhir,
                'keterangan' => $record->keterangan ?? $record->lokasi_fisik ?? ''
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Klasifikasi',
            'Nama Berkas',
            'Tanggal Buat Berkas',
            'Kurun Waktu',
            'Jumlah Item',
            'Retensi Aktif',
            'Retensi Inaktif',
            'Status Akhir',
            'Keterangan'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Kode Klasifikasi (narrower since no longer combined with nomor berkas)
            'C' => 40,  // Nama Berkas
            'D' => 15,  // Tanggal Buat Berkas
            'E' => 25,  // Kurun Waktu
            'F' => 10,  // Jumlah Item
            'G' => 12,  // Retensi Aktif
            'H' => 14,  // Retensi Inaktif
            'I' => 12,  // Status Akhir
            'J' => 30,  // Keterangan
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
                
                // Insert 4 rows at the top for title
                $sheet->insertNewRowBefore(1, 4);
                
                // Set title in row 1
                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', 'LAPORAN DAFTAR BERKAS ARSIP');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Set unit pengolah in row 2
                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A2', 'UNIT PENGOLAH: ' . $this->unitPengolah);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Set periode in row 3
                $sheet->mergeCells('A3:J3');
                $sheet->setCellValue('A3', 'PERIODE: ' . $this->periode);
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Move headings to row 5
                $headings = $this->headings();
                foreach ($headings as $colIndex => $heading) {
                    $col = chr(65 + $colIndex);
                    $sheet->setCellValue($col . '5', $heading);
                }

                // Header row style (row 5)
                $sheet->getStyle('A5:J5')->applyFromArray([
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
                $sheet->getRowDimension(5)->setRowHeight(30);

                $highestRow = $sheet->getHighestRow();
                $highestColumn = 'J';

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

                // Wrap text for the 'Nama Berkas' and 'Keterangan' columns
                $sheet->getStyle('C6:C' . $highestRow)->getAlignment()->setWrapText(true);
                $sheet->getStyle('J6:J' . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
<?php

namespace App\Exports;

use App\Models\BerkasArsip;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BerkasArsipLaporanExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->records as $index => $record) {
            $rows[] = [
                'no' => $index + 1,
                'kode_klasifikasi' => $record->klasifikasi->kode_klasifikasi ?? 'N/A',
                'nomor_berkas' => $record->nomor_berkas,
                'nama_berkas' => $record->nama_berkas,
                'tanggal_buat_berkas' => $record->created_at->format('d-m-Y'),
                'kurun_waktu' => $record->created_at->format('d M Y') . ' s/d ' . $record->updated_at->format('d M Y'),
                'jumlah_item' => 1, // Default to 1 as in the original
                'retensi_aktif' => $record->retensi_aktif,
                'retensi_inaktif' => $record->retensi_inaktif,
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
            'Nomor Berkas',
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
            'B' => 15,  // Kode Klasifikasi
            'C' => 15,  // Nomor Berkas
            'D' => 30,  // Nama Berkas
            'E' => 15,  // Tanggal Buat Berkas
            'F' => 25,  // Kurun Waktu
            'G' => 10,  // Jumlah Item
            'H' => 12,  // Retensi Aktif
            'I' => 14,  // Retensi Inaktif
            'J' => 12,  // Status Akhir
            'K' => 30,  // Keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6E6E6'],
            ],
        ];

        // Apply header style to the first row
        $sheet->getStyle(1)->applyFromArray($headerStyle);

        // Apply borders to the entire range
        $lastColumn = count($this->headings());
        $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColumn);
        $highestRow = $sheet->getHighestRow();
        $range = 'A1:' . $lastColumnLetter . $highestRow;

        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Center align the 'No' and 'Jumlah Item' columns
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Wrap text for the 'Nama Berkas' and 'Keterangan' columns
        $sheet->getStyle('D:D')->getAlignment()->setWrapText(true);
        $sheet->getStyle('K:K')->getAlignment()->setWrapText(true);

        return [];
    }
}
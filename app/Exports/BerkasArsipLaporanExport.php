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

class BerkasArsipLaporanExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
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
        return [
            1 => ['font' => ['bold' => true]], // Bold header row
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $highestRow = $event->sheet->getHighestRow();

                // Apply borders to the entire range
                $highestColumn = 'J';
                $range = 'A1:' . $highestColumn . $highestRow;
                $event->sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Center align specific columns
                $event->sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('F:F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('G:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('H:H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Apply header background color (similar to PDF)
                $event->sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2'], // Match PDF header style
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // Wrap text for the 'Nama Berkas' and 'Keterangan' columns
                $event->sheet->getStyle('C:C')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('J:J')->getAlignment()->setWrapText(true);
            },
        ];
    }
}
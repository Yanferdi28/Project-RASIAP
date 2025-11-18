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

class ArsipUnitLaporanExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
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
                'kode_klasifikasi' => $record->kodeKlasifikasi->kode_klasifikasi ?? 'N/A',
                'indeks' => $record->indeks ?? '',
                'uraian_informasi' => $record->uraian_informasi ?? '',
                'tanggal' => $record->tanggal ? $record->tanggal->format('d-m-Y') : '',
                'jumlah_nilai' => $record->jumlah_nilai . ' ' . ($record->jumlah_satuan ?? ''),
                'tingkat_perkembangan' => $record->tingkat_perkembangan ?? '',
                'unit_pengolah' => $record->unitPengolah->nama_unit ?? 'N/A',
                'retensi_aktif' => $record->retensi_aktif ?? 0,
                'retensi_inaktif' => $record->retensi_inaktif ?? 0,
                'status' => $record->status ?? '',
                'keterangan' => $record->keterangan ?? ''
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
            'Status',
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
            'K' => 12,  // Status
            'L' => 30,  // Keterangan
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
                $highestColumn = 'L';
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
                $event->sheet->getStyle('E:E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('I:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('J:J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('K:K')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

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

                // Wrap text for the 'Uraian Informasi' and 'Keterangan' columns
                $event->sheet->getStyle('D:D')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('L:L')->getAlignment()->setWrapText(true);
            },
        ];
    }
}
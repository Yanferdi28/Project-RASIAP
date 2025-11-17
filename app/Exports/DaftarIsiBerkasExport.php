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

class DaftarIsiBerkasExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function array(): array
    {
        $query = BerkasArsip::with(['arsipUnits', 'klasifikasi', 'arsipUnits.kodeKlasifikasi', 'arsipUnits.unitPengolah'])
            ->orderBy('created_at', 'desc');

        // Terapkan filter tanggal jika ada
        if (isset($this->filters['created_from']) && $this->filters['created_from']) {
            $query->whereDate('created_at', '>=', $this->filters['created_from']);
        }

        if (isset($this->filters['created_until']) && $this->filters['created_until']) {
            $query->whereDate('created_at', '<=', $this->filters['created_until']);
        }

        $berkasArsips = $query->get();
        $rows = [];
        $rowCounter = 1;

        foreach ($berkasArsips as $index => $record) {
            $arsipUnits = $record->arsipUnits;
            $totalUnits = $arsipUnits->count();

            // First row: Archive header (matching PDF format) - main berkas row shows '-'
            $rows[] = [
                'no' => $rowCounter++,
                'kode_klasifikasi' => $record->klasifikasi->kode_klasifikasi ?? 'N/A',
                'nama_berkas' => $record->nama_berkas,
                'jumlah_item' => $totalUnits,
                'unit_pengolah' => '-',
                'tanggal' => '-',
                'uraian_informasi' => '-',
                'jumlah_nilai' => 0, // Default for main archive header
                'jumlah_satuan' => 0, // Default for main archive header
                'no_item_arsip' => '-', // Main berkas row shows '-' as requested
                'keterangan' => '-',
                'status' => '-',
                'is_archive_header' => true,
            ];

            // Following rows: Related units (matching PDF format with sequential numbering)
            if($totalUnits > 0) {
                foreach($arsipUnits as $unitIndex => $unit) {
                    $sequentialNumber = $unitIndex + 1; // Sequential numbering: 1, 2, 3, etc.
                    $rows[] = [
                        'no' => '-', // No number for associated archives in the first column
                        'kode_klasifikasi' => ($unit->kodeKlasifikasi->kode_klasifikasi ?? 'N/A') . ' / ' . $sequentialNumber, // Use sequential number instead of database value
                        'nama_berkas' => $unit->uraian_informasi ?? '-',
                        'jumlah_item' => '-',
                        'unit_pengolah' => $unit->unitPengolah->nama_unit ?? 'N/A',
                        'tanggal' => $unit->tanggal ? $unit->tanggal->format('d-m-Y') : '-',
                        'uraian_informasi' => $unit->uraian_informasi ?? '-',
                        'jumlah_nilai' => $unit->jumlah_nilai ?? 0, // Ensure it shows 0 if null
                        'jumlah_satuan' => $unit->jumlah_satuan ?? 0, // Ensure it shows 0 if null
                        'no_item_arsip' => $sequentialNumber, // Use sequential numbering instead of database value for arsip units
                        'keterangan' => $unit->keterangan ?? '-',
                        'status' => ucfirst($unit->status) ?? '-',
                        'is_archive_header' => false,
                    ];
                }
            } else {
                $rows[] = [
                    'no' => '-',
                    'kode_klasifikasi' => '-', // Only kode_klasifikasi
                    'nama_berkas' => 'Tidak ada unit arsip terkait',
                    'jumlah_item' => '-',
                    'unit_pengolah' => '-',
                    'tanggal' => '-',
                    'uraian_informasi' => '-',
                    'jumlah_nilai' => 0,
                    'jumlah_satuan' => 0,
                    'no_item_arsip' => '-', // No sequential number when there are no units
                    'keterangan' => '-',
                    'status' => '-',
                    'is_archive_header' => false,
                ];
            }
        }

        // Hapus kolom is_archive_header dari hasil akhir karena hanya digunakan untuk styling
        $cleanRows = [];
        foreach ($rows as $row) {
            unset($row['is_archive_header']);
            $cleanRows[] = $row;
        }

        return $cleanRows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Klasifikasi / No Item Arsip',
            'Nama Berkas',
            'Jumlah Item',
            'Unit Pengolah',
            'Tanggal',
            'Uraian Informasi',
            'Jumlah Nilai',
            'Jumlah Satuan',
            'No Item Arsip',
            'Keterangan',
            'Status',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 20,  // Kode Klasifikasi (wider to accommodate combined content for related units)
            'C' => 30,  // Nama Berkas
            'D' => 10,  // Jumlah Item
            'E' => 20,  // Unit Pengolah
            'F' => 12,  // Tanggal
            'G' => 30,  // Uraian Informasi
            'H' => 12,  // Jumlah Nilai
            'I' => 12,  // Jumlah Satuan
            'J' => 15,  // No Item Arsip
            'K' => 20,  // Keterangan
            'L' => 15,  // Status
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

                // Iterate through each row to style based on whether it's an archive header or archive unit
                $rowNumber = 2; // Start from row 2 (after header)

                while ($rowNumber <= $highestRow) {
                    // Check if this is an archive header row by checking if the 'Jumlah Item' column contains a number
                    $jumlahItemValue = $event->sheet->getCell('D' . $rowNumber)->getValue();

                    if (is_numeric($jumlahItemValue) && $jumlahItemValue > 0) {
                        // This is an archive header row (Bold and background color)
                        $event->sheet->getStyle('A' . $rowNumber . ':L' . $rowNumber)->applyFromArray([
                            'font' => [
                                'bold' => true,
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D3D3D3'], // Match PDF header style
                            ],
                        ]);
                    } else if ($jumlahItemValue === 0) {
                        // This is an archive header with no items
                        $event->sheet->getStyle('A' . $rowNumber . ':L' . $rowNumber)->applyFromArray([
                            'font' => [
                                'bold' => true,
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D3D3D3'], // Match PDF header style
                            ],
                        ]);
                    } else {
                        // This is an archive unit row (Light background color)
                        $event->sheet->getStyle('A' . $rowNumber . ':L' . $rowNumber)->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9F9F9'], // Match PDF unit style
                            ],
                        ]);
                    }

                    $rowNumber++;
                }

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
                $event->sheet->getStyle('D:D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('H:H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('I:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
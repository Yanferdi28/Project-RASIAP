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
        $query = BerkasArsip::with(['arsipUnits', 'klasifikasi', 'arsipUnits.unitPengolah'])
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
        $berkasArsipCounter = 1;

        foreach ($berkasArsips as $berkasArsip) {
            $arsipUnits = $berkasArsip->arsipUnits;
            $jumlahItem = $arsipUnits->count();

            // Row pertama untuk BerkasArsip
            $rows[] = [
                'no' => $berkasArsipCounter . '.',
                'nama_berkas' => $berkasArsip->nama_berkas,
                'jumlah_item' => $jumlahItem,
                'uraian_informasi' => $berkasArsip->uraian ?? '',
                'tanggal' => $berkasArsip->created_at ? $berkasArsip->created_at->format('d-m-Y') : null,
                'unit_pengolah' => $berkasArsip->klasifikasi->kode_klasifikasi ?? 'N/A',
                'is_berkas_arsip' => true,
            ];

            $berkasArsipCounter++;

            // Tambahkan row untuk setiap ArsipUnit terkait
            foreach ($arsipUnits as $index => $arsipUnit) {
                $rows[] = [
                    'no' => '   ' . ($index + 1), // Indentasi untuk menunjukkan bahwa ini anak dari berkas arsip
                    'nama_berkas' => $arsipUnit->uraian_informasi,
                    'jumlah_item' => '-',
                    'uraian_informasi' => $arsipUnit->uraian_informasi,
                    'tanggal' => $arsipUnit->tanggal ? $arsipUnit->tanggal->format('d-m-Y') : null,
                    'unit_pengolah' => $arsipUnit->unitPengolah->nama_unit ?? '',
                    'is_berkas_arsip' => false,
                ];
            }
        }

        // Hapus kolom is_berkas_arsip dari hasil akhir karena hanya digunakan untuk styling
        $cleanRows = [];
        foreach ($rows as $row) {
            unset($row['is_berkas_arsip']);
            $cleanRows[] = $row;
        }

        return $cleanRows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Berkas',
            'Jumlah Item',
            'Uraian Informasi',
            'Tanggal',
            'Unit Pengolah',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 40,
            'C' => 15,
            'D' => 40,
            'E' => 15,
            'F' => 30,
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

                // Iterate through each row to style based on whether it's an berkas_arsip or arsip_unit
                $rowNumber = 2; // Start from row 2 (after header)

                while ($rowNumber <= $highestRow) {
                    // Check if the 'No' column starts with a number followed by a dot (indicating berkas_arsip)
                    $noValue = $event->sheet->getCell('A' . $rowNumber)->getValue();

                    if (preg_match('/^\d+\.$/', trim($noValue))) {
                        // This is an BerkasArsip row (Bold and background color)
                        $event->sheet->getStyle('A' . $rowNumber . ':F' . $rowNumber)->applyFromArray([
                            'font' => [
                                'bold' => true,
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'E6F3FF'],
                            ],
                        ]);
                    }

                    $rowNumber++;
                }
            },
        ];
    }
}
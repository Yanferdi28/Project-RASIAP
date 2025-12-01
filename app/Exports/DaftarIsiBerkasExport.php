<?php

namespace App\Exports;

use App\Models\BerkasArsip;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DaftarIsiBerkasExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents, WithTitle
{
    protected $filters;
    protected $unitPengolah;
    protected $periode;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        
        // Ambil unit pengolah dari user yang login
        $user = auth()->user();
        $this->unitPengolah = $user->unitPengolah->nama_unit ?? 'Unit Pengolah';
        
        $dari = $filters['created_from'] ?? now()->subMonth()->format('d/m/Y');
        $sampai = $filters['created_until'] ?? now()->format('d/m/Y');
        $this->periode = \Carbon\Carbon::parse($dari)->format('d F Y') . ' - ' . \Carbon\Carbon::parse($sampai)->format('d F Y');
    }

    public function title(): string
    {
        return 'Daftar Isi Berkas';
    }

    public function array(): array
    {
        $query = BerkasArsip::with(['arsipUnits' => function($q) {
                $q->orderBy('created_at', 'asc');
            }, 'klasifikasi', 'unitPengolah', 'arsipUnits.kodeKlasifikasi', 'arsipUnits.unitPengolah'])
            ->orderBy('created_at', 'asc');

        // Terapkan filter tanggal jika ada
        if (isset($this->filters['created_from']) && $this->filters['created_from']) {
            $query->whereDate('created_at', '>=', $this->filters['created_from']);
        }

        if (isset($this->filters['created_until']) && $this->filters['created_until']) {
            $query->whereDate('created_at', '<=', $this->filters['created_until']);
        }

        $berkasArsips = $query->get();
        $rows = [];
        $noBerkas = 1;

        foreach ($berkasArsips as $record) {
            $arsipUnits = $record->arsipUnits;
            $totalUnits = $arsipUnits->count();
            $totalJumlah = $arsipUnits->sum('jumlah_nilai');

            if ($totalUnits > 0) {
                foreach ($arsipUnits as $unitIndex => $unit) {
                    $noItem = $unitIndex + 1;
                    $isFirst = ($unitIndex === 0);
                    
                    $rows[] = [
                        'no' => $isFirst ? $noBerkas : '',
                        'kode_klasifikasi' => $isFirst ? ($record->klasifikasi->kode_klasifikasi ?? '-') : '',
                        'nama_berkas' => $isFirst ? $record->nama_berkas : '',
                        'tanggal_berkas' => $isFirst ? ($record->created_at ? $record->created_at->format('d/m/Y') : '-') : '',
                        'no_item' => $noItem,
                        'uraian_informasi' => $unit->uraian_informasi ?? '-',
                        'tanggal_item' => $unit->tanggal ? $unit->tanggal->format('d-m-Y') : '-',
                        'jumlah' => $isFirst ? $totalJumlah : '',
                        'keterangan' => $unit->tingkat_perkembangan ?? '-',
                    ];
                }
            } else {
                $rows[] = [
                    'no' => $noBerkas,
                    'kode_klasifikasi' => $record->klasifikasi->kode_klasifikasi ?? '-',
                    'nama_berkas' => $record->nama_berkas,
                    'tanggal_berkas' => $record->created_at ? $record->created_at->format('d/m/Y') : '-',
                    'no_item' => '-',
                    'uraian_informasi' => 'Tidak ada item arsip',
                    'tanggal_item' => '-',
                    'jumlah' => 0,
                    'keterangan' => '-',
                ];
            }
            
            $noBerkas++;
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE KLASIFIKASI / NOMOR BERKAS',
            'NAMA BERKAS',
            'TANGGAL BUAT BERKAS',
            'NO ITEM ARSIP',
            'URAIAN INFORMASI ARSIP',
            'TANGGAL ITEM',
            'JUMLAH',
            'KETERANGAN',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Kode Klasifikasi
            'C' => 25,  // Nama Berkas
            'D' => 12,  // Tanggal Buat Berkas
            'E' => 8,   // No Item Arsip
            'F' => 50,  // Uraian Informasi
            'G' => 12,  // Tanggal Item
            'H' => 8,   // Jumlah
            'I' => 15,  // Keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Title styles
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'LAPORAN DAFTAR ISI BERKAS ARSIP AKTIF');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'UNIT PENGOLAH: ' . $this->unitPengolah);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'PERIODE: ' . $this->periode);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header row style (row 5)
        $sheet->getStyle('A5:I5')->applyFromArray([
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

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Insert 4 rows at the top for title
                $sheet->insertNewRowBefore(1, 4);
                
                // Move headings to row 5
                $headings = $this->headings();
                foreach ($headings as $colIndex => $heading) {
                    $col = chr(65 + $colIndex); // A, B, C, ...
                    $sheet->setCellValue($col . '5', $heading);
                }

                $highestRow = $sheet->getHighestRow();
                $highestColumn = 'I';

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

                // Wrap text for uraian column
                $sheet->getStyle('F6:F' . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
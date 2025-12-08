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
            }, 'klasifikasi', 'arsipUnits.kodeKlasifikasi', 'arsipUnits.unitPengolah'])
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

            // Row berkas (tanpa indeks)
            $rows[] = [
                'no' => $noBerkas,
                'kode_klasifikasi' => $record->klasifikasi->kode_klasifikasi ?? '-',
                'indeks' => '',
                'nama_berkas' => $record->nama_berkas,
                'tanggal_berkas' => $record->created_at ? $record->created_at->format('d/m/Y') : '-',
                'no_item' => '',
                'uraian_informasi' => $record->uraian ?? '-',
                'tanggal_item' => '',
                'tingkat_perkembangan' => '',
                'jumlah_item' => $totalUnits,
                'retensi_aktif' => $record->retensi_aktif ?? '-',
                'retensi_inaktif' => $record->retensi_inaktif ?? '-',
                'skkaad' => $record->klasifikasi->klasifikasi_keamanan ?? '-',
                'status_akhir' => $record->klasifikasi->status_akhir ?? '-',
                'lokasi_berkas' => $record->lokasi_fisik ?? '-',
                'ruang' => '',
                'no_rak' => '',
                'no_laci' => '',
                'no_box' => '',
                'no_folder' => '',
                'keterangan' => '',
            ];

            // Row unit arsip (dengan indeks)
            if ($totalUnits > 0) {
                foreach ($arsipUnits as $unitIndex => $unit) {
                    $noItem = $unitIndex + 1;
                    
                    $rows[] = [
                        'no' => '',
                        'kode_klasifikasi' => '',
                        'indeks' => $unit->indeks ?? '-',
                        'nama_berkas' => '',
                        'tanggal_berkas' => '',
                        'no_item' => $noItem,
                        'uraian_informasi' => $unit->uraian_informasi ?? '-',
                        'tanggal_item' => $unit->tanggal ? $unit->tanggal->format('d-m-Y') : '-',
                        'tingkat_perkembangan' => $unit->tingkat_perkembangan ?? '-',
                        'jumlah_item' => '',
                        'retensi_aktif' => '',
                        'retensi_inaktif' => '',
                        'skkaad' => '',
                        'status_akhir' => '',
                        'lokasi_berkas' => '',
                        'ruang' => $unit->ruangan ?? '-',
                        'no_rak' => $unit->no_filling ?? '-',
                        'no_laci' => $unit->no_laci ?? '-',
                        'no_box' => $unit->no_box ?? '-',
                        'no_folder' => $unit->no_folder ?? '-',
                        'keterangan' => $unit->keterangan ?? '-',
                    ];
                }
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
            'INDEKS',
            'NAMA BERKAS',
            'TANGGAL BUAT BERKAS',
            'NO ITEM ARSIP',
            'URAIAN INFORMASI',
            'TANGGAL',
            'TINGKAT PERKEMBANGAN',
            'JUMLAH ITEM',
            'RETENSI AKTIF',
            'RETENSI INAKTIF',
            'SKKAAD',
            'STATUS AKHIR',
            'LOKASI BERKAS',
            'Ruang',
            'No Rak',
            'No Laci',
            'No Box',
            'No Folder',
            'KETERANGAN',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Kode Klasifikasi
            'C' => 15,  // Indeks
            'D' => 25,  // Nama Berkas
            'E' => 12,  // Tanggal Buat Berkas
            'F' => 8,   // No Item Arsip
            'G' => 40,  // Uraian Informasi
            'H' => 12,  // Tanggal Item
            'I' => 15,  // Tingkat Perkembangan
            'J' => 10,  // Jumlah Item
            'K' => 10,  // Retensi Aktif
            'L' => 10,  // Retensi Inaktif
            'M' => 10,  // SKKAAD
            'N' => 12,  // Status Akhir
            'O' => 20,  // Lokasi Berkas
            'P' => 10,  // Ruang
            'Q' => 10,  // No Rak
            'R' => 10,  // No Laci
            'S' => 10,  // No Box
            'T' => 10,  // No Folder
            'U' => 15,  // Keterangan
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
                $sheet->mergeCells('A1:U1');
                $sheet->setCellValue('A1', 'LAPORAN DAFTAR ISI BERKAS ARSIP AKTIF');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Set unit pengolah in row 2
                $sheet->mergeCells('A2:U2');
                $sheet->setCellValue('A2', 'UNIT PENGOLAH: ' . $this->unitPengolah);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Set periode in row 3
                $sheet->mergeCells('A3:U3');
                $sheet->setCellValue('A3', 'PERIODE: ' . $this->periode);
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Row 5: Main headers (merge columns A-O vertically, P-T for "Lokasi Arsip", U for Keterangan)
                $mainHeaders = ['NO', 'KODE KLASIFIKASI / NOMOR BERKAS', 'INDEKS', 'NAMA BERKAS', 'TANGGAL BUAT BERKAS', 
                               'NO ITEM ARSIP', 'URAIAN INFORMASI', 'TANGGAL', 'TINGKAT PERKEMBANGAN', 'JUMLAH ITEM', 
                               'RETENSI AKTIF', 'RETENSI INAKTIF', 'SKKAAD', 'STATUS AKHIR', 'LOKASI BERKAS'];
                foreach ($mainHeaders as $colIndex => $heading) {
                    $col = chr(65 + $colIndex);
                    $sheet->mergeCells($col . '5:' . $col . '6');
                    $sheet->setCellValue($col . '5', $heading);
                }
                
                // Merge P5:T5 for "Lokasi Arsip" header
                $sheet->mergeCells('P5:T5');
                $sheet->setCellValue('P5', 'Lokasi Arsip');
                
                // Row 6: Sub-headers for Lokasi Arsip
                $lokasiHeaders = ['Ruang', 'No Rak', 'No Laci', 'No Box', 'No Folder'];
                $startCol = 15; // P = 15 (0-indexed)
                foreach ($lokasiHeaders as $idx => $heading) {
                    $col = chr(65 + $startCol + $idx);
                    $sheet->setCellValue($col . '6', $heading);
                }
                
                // Keterangan column (U) - merge vertically
                $sheet->mergeCells('U5:U6');
                $sheet->setCellValue('U5', 'KETERANGAN');

                // Header row style (row 5-6)
                $sheet->getStyle('A5:U6')->applyFromArray([
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
                $highestColumn = 'U';

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

                // Wrap text for uraian (G), lokasi berkas (O) and keterangan (U) columns
                $sheet->getStyle('G7:G' . $highestRow)->getAlignment()->setWrapText(true);
                $sheet->getStyle('O7:O' . $highestRow)->getAlignment()->setWrapText(true);
                $sheet->getStyle('U7:U' . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
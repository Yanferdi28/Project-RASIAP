<?php

namespace App\Exports;

use App\Models\ArsipAktif;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArsipAktifExport implements FromQuery, WithMapping, WithHeadings, WithColumnWidths, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = ArsipAktif::query();
        
        // Terapkan filter tanggal jika ada
        if (isset($this->filters['created_from']) && $this->filters['created_from']) {
            $query->whereDate('created_at', '>=', $this->filters['created_from']);
        }
        
        if (isset($this->filters['created_until']) && $this->filters['created_until']) {
            $query->whereDate('created_at', '<=', $this->filters['created_until']);
        }
        
        return $query;
    }

    public function map($arsipAktif): array
    {
        return [
            'nama_berkas' => $arsipAktif->nama_berkas,
            'kode_klasifikasi' => $arsipAktif->klasifikasi->kode_klasifikasi ?? '',
            'retensi_aktif' => $arsipAktif->retensi_aktif,
            'retensi_inaktif' => $arsipAktif->retensi_inaktif,
            'penyusutan_akhir' => $arsipAktif->penyusutan_akhir,
            'lokasi_fisik' => $arsipAktif->lokasi_fisik,
            'uraian' => $arsipAktif->uraian,
            'created_at' => $arsipAktif->created_at ? $arsipAktif->created_at->format('Y-m-d') : null,
        ];
    }

    public function headings(): array
    {
        return [
            'nama_berkas',
            'kode_klasifikasi',
            'retensi_aktif',
            'retensi_inaktif',
            'penyusutan_akhir',
            'lokasi_fisik',
            'uraian',
            'created_at',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 15,
            'D' => 15,
            'E' => 20,
            'F' => 25,
            'G' => 20,
            'H' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold header row
        ];
    }
}
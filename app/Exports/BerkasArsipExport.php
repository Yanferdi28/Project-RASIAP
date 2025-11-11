<?php

namespace App\Exports;

use App\Models\BerkasArsip;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BerkasArsipExport implements FromQuery, WithMapping, WithHeadings, WithColumnWidths, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = BerkasArsip::query();

        // Terapkan filter tanggal jika ada
        if (isset($this->filters['created_from']) && $this->filters['created_from']) {
            $query->whereDate('created_at', '>=', $this->filters['created_from']);
        }

        if (isset($this->filters['created_until']) && $this->filters['created_until']) {
            $query->whereDate('created_at', '<=', $this->filters['created_until']);
        }

        return $query;
    }

    public function map($berkasArsip): array
    {
        return [
            'nama_berkas' => $berkasArsip->nama_berkas,
            'kode_klasifikasi' => $berkasArsip->klasifikasi->kode_klasifikasi ?? '',
            'retensi_aktif' => $berkasArsip->retensi_aktif,
            'retensi_inaktif' => $berkasArsip->retensi_inaktif,
            'penyusutan_akhir' => $berkasArsip->penyusutan_akhir,
            'lokasi_fisik' => $berkasArsip->lokasi_fisik,
            'uraian' => $berkasArsip->uraian,
            'created_at' => $berkasArsip->created_at ? $berkasArsip->created_at->format('Y-m-d') : null,
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
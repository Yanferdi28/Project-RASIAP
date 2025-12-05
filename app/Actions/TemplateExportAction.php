<?php

namespace App\Actions;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArsipUnitTemplateExport;
use App\Exports\DaftarIsiBerkasTemplateExport;
use App\Exports\TemplateImportArsipUnitExport;
use Barryvdh\DomPDF\Facade\Pdf;

class TemplateExportAction
{
    public static function arsipUnitExcel()
    {
        return Excel::download(
            new ArsipUnitTemplateExport(),
            'template-arsip-unit.xlsx'
        );
    }

    public static function arsipUnitPdf()
    {
        $user = auth()->user();
        $unitPengolah = $user->unitPengolah->nama_unit ?? 'Unit Pengolah';
        $periode = now()->format('d F Y');

        $pdf = Pdf::loadView('pdf.template-arsip-unit', [
            'unitPengolah' => $unitPengolah,
            'periode' => $periode,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('template-arsip-unit.pdf');
    }

    public static function daftarIsiBerkasExcel()
    {
        return Excel::download(
            new DaftarIsiBerkasTemplateExport(),
            'template-daftar-isi-berkas.xlsx'
        );
    }

    public static function daftarIsiBerkasPdf()
    {
        $user = auth()->user();
        $unitPengolah = $user->unitPengolah->nama_unit ?? 'Unit Pengolah';
        $periode = now()->format('d F Y');

        $pdf = Pdf::loadView('pdf.template-daftar-isi-berkas', [
            'unitPengolah' => $unitPengolah,
            'periode' => $periode,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('template-daftar-isi-berkas.pdf');
    }

    public static function importArsipUnitExcel()
    {
        return Excel::download(
            new TemplateImportArsipUnitExport(),
            'template-import-arsip-unit.xlsx'
        );
    }
}

<?php

namespace App\Actions;

use App\Exports\DaftarIsiBerkasExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DaftarIsiBerkasDropdownAction
{
    public static function make(): Action
    {
        return Action::make('cetak_daftar_isi_berkas')
            ->label('Cetak Daftar Isi Berkas')
            ->icon('heroicon-o-arrow-down-tray')
            ->modalWidth('md') 
            ->modalSubmitAction(false) 
            ->modalCancelActionLabel('Tutup')
            ->modalContent(view('actions.daftar-isi-berkas-modal', [
                'pdfAction' => 'cetak_daftar_isi_berkas_pdf',
                'excelAction' => 'cetak_daftar_isi_berkas_excel',
            ]))
            ->modalHeading('Pilih Format Ekspor')
            ->action(function () {
                // This is just to prevent errors since we're using modal content
                // The actual actions will be handled by buttons in the modal content
            });
    }
}
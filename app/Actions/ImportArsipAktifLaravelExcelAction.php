<?php

namespace App\Actions;

use App\Imports\ArsipAktifImport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportArsipAktifLaravelExcelAction
{
    public static function make(): Action
    {
        return Action::make('import_arsip_aktif_laravel_excel')
            ->label('Impor Arsip Aktif (Excel)')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('warning')
            ->requiresConfirmation()
            ->modalHeading('Impor Arsip Aktif')
            ->modalDescription('Pilih file Excel yang berisi data arsip aktif untuk diimpor.')
            ->action(function (array $data) {
                try {
                    $filePath = $data['file'];
                    
                    // Import data
                    $import = new ArsipAktifImport();
                    Excel::import($import, $filePath);
                    
                    // Berikan notifikasi sukses
                    Notification::make()
                        ->title('Impor Berhasil')
                        ->body('Data arsip aktif berhasil diimpor.')
                        ->success()
                        ->send();
                        
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Gagal Mengimpor')
                        ->body('Terjadi kesalahan saat mengimpor data: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            })
            ->form([
                \Filament\Forms\Components\FileUpload::make('file')
                    ->label('File Excel')
                    ->required()
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'])
                    ->maxSize(2048) // 2MB
                    ->helperText('Format yang didukung: XLSX, XLS, CSV. Unduh template: /templates/template_import_arsip_aktif.csv'),
            ]);
    }
}
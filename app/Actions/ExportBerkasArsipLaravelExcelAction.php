<?php

namespace App\Actions;

use App\Exports\BerkasArsipExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ExportBerkasArsipLaravelExcelAction
{
    public static function make(): Action
    {
        return Action::make('export_berkas_arsip_laravel_excel')
            ->label('Ekspor Berkas Arsip (Excel)')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Ekspor Berkas Arsip')
            ->modalDescription('Pilih rentang tanggal untuk mengekspor data berkas arsip.')
            ->action(function (array $data) {
                try {
                    // Generate filename
                    $filename = 'berkas_arsip_' . date('Y-m-d_H-i-s') . '.xlsx';

                    // Create export instance with filters
                    $export = new BerkasArsipExport([
                        'created_from' => $data['created_from'] ?? null,
                        'created_until' => $data['created_until'] ?? null,
                    ]);

                    // Send success notification before download
                    Notification::make()
                        ->title('Ekspor Dimulai')
                        ->body('File ekspor sedang disiapkan...')
                        ->success()
                        ->send();

                    // Return Excel download response
                    return Excel::download($export, $filename);
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Gagal Mengekspor')
                        ->body('Terjadi kesalahan saat mengekspor data: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            })
            ->form([
                \Filament\Forms\Components\DatePicker::make('created_from')
                    ->label('Dari Tanggal')
                    ->displayFormat('d/m/Y')
                    ->extraInputAttributes(['placeholder' => 'Pilih tanggal mulai']),

                \Filament\Forms\Components\DatePicker::make('created_until')
                    ->label('Sampai Tanggal')
                    ->displayFormat('d/m/Y')
                    ->extraInputAttributes(['placeholder' => 'Pilih tanggal akhir']),
            ]);
    }
}
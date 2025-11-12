<?php

namespace App\Actions;

use App\Services\ArsipUnitImportService;
use Filament\Actions\Action;
use Illuminate\Http\UploadedFile;

class ImportArsipUnitAction
{
    public static function make(): Action
    {
        return Action::make('import_arsip_unit')
            ->label('Impor Arsip Unit Excel')
            ->color('success')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                \Filament\Forms\Components\FileUpload::make('file')
                    ->label('File Excel/CSV')
                    ->required()
                    ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                    ->maxSize(10240) // 10MB
                    ->storeFiles(false)
                    ->helperText('Format yang didukung: XLS, XLSX, CSV. Kolom Retensi Aktif, Retensi Inaktif, dan SKKAAD akan otomatis terisi dari data Kode Klasifikasi jika dikosongkan.'),
            ])
            ->action(function (array $data) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $data['file'];
                
                $importService = new ArsipUnitImportService();
                $result = $importService->importFile($uploadedFile);

                // Send notification based on result
                $notification = \Filament\Notifications\Notification::make()
                    ->title('Impor Arsip Unit Selesai')
                    ->body(
                        'Berhasil: ' . $result['success_count'] . ' baris, ' .
                        'Gagal: ' . $result['error_count'] . ' baris.' .
                        ($result['error_count'] > 0 ? ' Lihat log untuk detail kesalahan.' : '')
                    );

                if ($result['error_count'] === 0) {
                    $notification->success();
                } else {
                    $notification->warning();
                    // Add error details as additional notifications or in the description
                    if (!empty($result['errors'])) {
                        $errorDetails = implode("\n", array_slice($result['errors'], 0, 5)); // Show first 5 errors
                        if (count($result['errors']) > 5) {
                            $errorDetails .= "\n... dan " . (count($result['errors']) - 5) . " kesalahan lainnya";
                        }
                        $notification->body($notification->getbody() . "\n\nKesalahan: " . $errorDetails);
                    }
                }

                $notification->send();
            })
            ->modalHeading('Impor Arsip Unit dari Excel/CSV')
            ->modalWidth('md')
            ->modalButton('Impor Sekarang');
    }
}
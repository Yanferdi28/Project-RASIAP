<?php

namespace App\Actions;

use App\Models\ArsipAktif;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ExportArsipAktifAction
{
    public static function make(): Action
    {
        return Action::make('export_arsip_aktif')
            ->label('Ekspor Arsip Aktif (CSV)')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(function (array $data) {
                // Buat nama file
                $filename = 'arsip_aktif_' . date('Y-m-d_H-i-s') . '.csv';
                
                // Query data dengan filter
                $query = ArsipAktif::query();
                
                if (isset($data['created_from']) && $data['created_from']) {
                    $query->whereDate('created_at', '>=', $data['created_from']);
                }
                
                if (isset($data['created_until']) && $data['created_until']) {
                    $query->whereDate('created_at', '<=', $data['created_until']);
                }
                
                $arsipAktif = $query->get();
                
                // Generate CSV content
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ];
                
                $callback = function() use ($arsipAktif) {
                    $file = fopen('php://output', 'w');
                    
                    // Tulis header
                    fputcsv($file, [
                        'Nomor Berkas',
                        'Nama Berkas',
                        'Klasifikasi',
                        'Retensi Aktif',
                        'Retensi Inaktif',
                        'Penyusutan Akhir',
                        'Lokasi Fisik',
                        'Uraian',
                        'Kategori',
                        'Sub Kategori',
                        'Tanggal Pembuatan'
                    ]);
                    
                    // Tulis data
                    foreach ($arsipAktif as $item) {
                        fputcsv($file, [
                            $item->nomor_berkas,
                            $item->nama_berkas,
                            $item->klasifikasi->kode_klasifikasi ?? '',
                            $item->retensi_aktif,
                            $item->retensi_inaktif,
                            $item->penyusutan_akhir,
                            $item->lokasi_fisik,
                            $item->uraian,
                            $item->kategori->nama_kategori ?? '',
                            $item->subKategori->nama_sub_kategori ?? '',
                            $item->created_at,
                        ]);
                    }
                    
                    fclose($file);
                };
                
                // Kirim file
                return response()->stream($callback, 200, $headers);
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
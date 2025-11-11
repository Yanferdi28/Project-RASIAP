<?php

namespace App\Actions;

use App\Models\BerkasArsip;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ExportBerkasArsipAction
{
    public static function make(): Action
    {
        return Action::make('export_berkas_arsip')
            ->label('Ekspor Berkas Arsip (CSV)')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(function (array $data) {
                // Buat nama file
                $filename = 'berkas_arsip_' . date('Y-m-d_H-i-s') . '.csv';

                // Query data dengan filter
                $query = BerkasArsip::query();

                if (isset($data['created_from']) && $data['created_from']) {
                    $query->whereDate('created_at', '>=', $data['created_from']);
                }

                if (isset($data['created_until']) && $data['created_until']) {
                    $query->whereDate('created_at', '<=', $data['created_until']);
                }

                $berkasArsip = $query->get();

                // Generate CSV content
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ];

                $callback = function() use ($berkasArsip) {
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
                    foreach ($berkasArsip as $item) {
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
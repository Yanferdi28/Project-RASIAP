<?php

namespace App\Actions;

use App\Exports\DaftarIsiBerkasExport;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DaftarIsiBerkasAction
{
    public static function make(): Action
    {
        return Action::make('cetak_daftar_isi_berkas')
            ->label('Cetak Daftar Isi Berkas')
            ->icon('heroicon-o-printer')
            ->requiresConfirmation()
            ->modalHeading('Cetak Daftar Isi Berkas Arsip Aktif')
            ->modalDescription('Pilih format ekspor dan rentang tanggal')
            ->form([
                Select::make('format_ekspor')
                    ->label('Format Ekspor')
                    ->options([
                        'pdf' => 'PDF',
                        'excel' => 'Excel',
                    ])
                    ->default('pdf')
                    ->required(),

                \Filament\Forms\Components\DatePicker::make('tanggal_dari')
                    ->label('Dari Tanggal')
                    ->displayFormat('d/m/Y')
                    ->extraInputAttributes(['placeholder' => 'Pilih tanggal mulai'])
                    ->required(),

                \Filament\Forms\Components\DatePicker::make('tanggal_sampai')
                    ->label('Sampai Tanggal')
                    ->displayFormat('d/m/Y')
                    ->extraInputAttributes(['placeholder' => 'Pilih tanggal akhir'])
                    ->required(),
            ])
            ->action(function (array $data, \Filament\Tables\Contracts\HasTable $livewire) {
                try {
                    // Buat query dasar dari tabel yang sudah difilter
                    $query = $livewire->getFilteredTableQuery()
                        ->with(['arsipUnits' => function($q) {
                            $q->orderBy('created_at', 'asc');
                        }, 'klasifikasi', 'arsipUnits.kodeKlasifikasi', 'arsipUnits.unitPengolah'])
                        ->orderBy('created_at', 'asc');

                    // Tambahkan filter berdasarkan tanggal jika disediakan
                    if (isset($data['tanggal_dari']) && $data['tanggal_dari']) {
                        $query->whereDate('created_at', '>=', $data['tanggal_dari']);
                    }

                    if (isset($data['tanggal_sampai']) && $data['tanggal_sampai']) {
                        $query->whereDate('created_at', '<=', $data['tanggal_sampai']);
                    }

                    $records = $query->get();

                    // Buat periode untuk ditampilkan di laporan
                    $dari = $data['tanggal_dari'] ?? now()->subMonth()->format('d/m/Y');
                    $sampai = $data['tanggal_sampai'] ?? now()->format('d/m/Y');
                    $periode = \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y');

                    // Ambil unit pengolah dari user yang login
                    $user = auth()->user();
                    $unitPengolah = $user->unitPengolah->nama_unit ?? 'Unit Pengolah';

                    $format = $data['format_ekspor'];

                    if ($format === 'pdf') {
                        $view = view('pdf.daftar-isi-berkas', compact('records', 'unitPengolah', 'periode'))->render();

                        $pdf = Pdf::loadHtml($view)
                                ->setPaper('a4', 'landscape');

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Daftar Isi Berkas Arsip Aktif.pdf'
                        );
                    } else { // Excel
                        $export = new DaftarIsiBerkasExport([
                            'created_from' => $data['tanggal_dari'] ?? null,
                            'created_until' => $data['tanggal_sampai'] ?? null,
                        ]);

                        $filename = 'daftar_isi_berkas_arsip_aktif_' . date('Y-m-d_H-i-s') . '.xlsx';

                        return Excel::download($export, $filename);
                    }
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Gagal Mengekspor')
                        ->body('Terjadi kesalahan saat mengekspor data: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
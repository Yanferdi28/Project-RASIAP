<?php

namespace App\Filament\Resources\ArsipInaktifs\Tables;

use App\Models\ArsipInaktif;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportAction;
use App\Filament\Exports\ArsipInaktifExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ArsipInaktifsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_berkas')
                    ->searchable(),

                TextColumn::make('klasifikasi')
                    ->label('Klasifikasi')
                    ->getStateUsing(function (ArsipInaktif $record) {
                        if ($record->klasifikasi) {
                            return "{$record->klasifikasi->kode_klasifikasi} - {$record->klasifikasi->uraian}";
                        }
                        return 'Tidak ada'; 
                    })
                    ->searchable(['klasifikasi.kode_klasifikasi', 'klasifikasi.uraian'])
                    ->sortable('klasifikasi.kode_klasifikasi'),

                TextColumn::make('retensi_aktif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('retensi_inaktif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('penyusutan_akhir')
                    ->searchable(),
                TextColumn::make('lokasi_fisik')
                    ->searchable(),
                TextColumn::make('kategori_berkas')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                ExportAction::make()
                    ->label('Ekspor ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ArsipInaktifExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
                    ->color('secondary'),

                Action::make('printCustomPdf')
                    ->label('Cetak Laporan PDF')
                    ->icon('heroicon-o-document-check')
                    ->color('danger')
                    ->action(function (\Filament\Tables\Contracts\HasTable $livewire) {
                        
                        $records = $livewire->getFilteredTableQuery()->get();
                        
                        $unitPengolah = 'RRI BANJARMASIN';
                        $periode = '01 JULI 2025-30 SEPTEMBER 2025';

                        $view = view('pdf.laporan-arsip-inaktif', compact('records', 'unitPengolah', 'periode'))->render();

                        $pdf = Pdf::loadHtml($view)
                                ->setPaper('a4', 'landscape');

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Laporan Daftar Berkas Arsip Inaktif.pdf'
                        );
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\ArsipUnits\Pages;

use App\Filament\Resources\ArsipUnits\ArsipUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

class ViewArsipUnit extends ViewRecord
{
    protected static string $resource = ArsipUnitResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('kodeKlasifikasi.kode_klasifikasi')
                    ->label('Kode Klasifikasi')
                    ->disabled()
                    ->formatStateUsing(function ($record) {
                        return $record?->kodeKlasifikasi?->kode_klasifikasi ?? 'Tidak ada';
                    }),
                TextInput::make('indeks')
                    ->label('Indeks')
                    ->disabled(),
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->disabled(),
                TextInput::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->disabled()
                    ->formatStateUsing(function ($record) {
                        return $record?->kategori?->nama_kategori ?? 'Tidak ada';
                    }),
                TextInput::make('subKategori.nama_sub_kategori')
                    ->label('Sub Kategori')
                    ->disabled()
                    ->formatStateUsing(function ($record) {
                        return $record?->subKategori?->nama_sub_kategori ?? 'Tidak ada';
                    }),
                TextInput::make('jumlah_nilai')
                    ->label('Jumlah Nilai')
                    ->disabled(),
                TextInput::make('jumlah_satuan')
                    ->label('Jumlah Satuan')
                    ->disabled(),
                TextInput::make('tingkat_perkembangan')
                    ->label('Tingkat Perkembangan')
                    ->disabled(),
                TextInput::make('skkaad')
                    ->label('SKKAAD')
                    ->disabled(),
                TextInput::make('retensi_aktif')
                    ->label('Retensi Aktif')
                    ->disabled(),
                TextInput::make('retensi_inaktif')
                    ->label('Retensi Inaktif')
                    ->disabled(),
                TextInput::make('ruangan')
                    ->label('Ruangan')
                    ->disabled(),
                TextInput::make('no_filling')
                    ->label('No. Filling')
                    ->disabled(),
                TextInput::make('no_laci')
                    ->label('No. Laci')
                    ->disabled(),
                TextInput::make('no_folder')
                    ->label('No. Folder')
                    ->disabled(),
                TextInput::make('no_box')
                    ->label('No. Box')
                    ->disabled(),
                Textarea::make('uraian_informasi')
                    ->label('Uraian Informasi')
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->label('Status')
                    ->disabled(),
                TextInput::make('verifier.name') // Changed from 'verifikasi_oleh.name' to use the relationship method
                    ->label('Diverifikasi Oleh')
                    ->disabled()
                    ->formatStateUsing(function ($record) {
                        return $record?->verifier?->name ?? 'Belum diverifikasi';
                    }),
                DatePicker::make('verifikasi_tanggal')
                    ->label('Tanggal Verifikasi')
                    ->disabled(),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('dokumen')
                    ->label('Dokumen')
                    ->disabled()
                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            $fileName = basename($state);
                            return $fileName;
                        }
                        return 'Tidak ada dokumen';
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_document')
                ->label('Download Dokumen')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->url(function ($record) {
                    if (empty($record) || empty($record->id_berkas)) {
                        return '#';
                    }
                    return route('dokumen.show', ['id' => $record->id_berkas, 'action' => 'download']);
                }, shouldOpenInNewTab: true)
                ->visible(fn ($record) => $record && !empty($record->dokumen)),
            
            Actions\Action::make('view_document')
                ->label('Lihat Dokumen')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(function ($record) {
                    if (empty($record) || empty($record->id_berkas)) {
                        return '#';
                    }
                    return route('dokumen.show', ['id' => $record->id_berkas, 'action' => 'view']);
                }, shouldOpenInNewTab: true)
                ->visible(fn ($record) => $record && !empty($record->dokumen)),
            
            Actions\EditAction::make(),
        ];
    }
}
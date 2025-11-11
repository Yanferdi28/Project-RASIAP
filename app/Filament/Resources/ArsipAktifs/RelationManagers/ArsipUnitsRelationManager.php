<?php

namespace App\Filament\Resources\ArsipAktifs\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use App\Models\KodeKlasifikasi;
use App\Models\Kategori;
use App\Models\Subkategori;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class ArsipUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'arsipUnits';

    protected static ?string $title = 'Unit Arsip Terkait';

    protected static ?string $recordTitleAttribute = 'id_berkas';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id_berkas')
            ->modifyQueryUsing(function (Builder $query) {
                // Order by created_at to ensure consistent numbering
                return $query->orderBy('created_at', 'asc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('no_urut')
                    ->label('No. Item')
                    ->getStateUsing(function ($record, $livewire) {
                        // Get the related arsip aktif record
                        $parentRecord = $livewire->getOwnerRecord();
                        
                        // Get all related arsip units ordered by creation date
                        $relatedUnits = $parentRecord->arsipUnits()
                            ->orderBy('created_at', 'asc')
                            ->get();
                        
                        // Find the position of the current record
                        $position = $relatedUnits->search(function ($item) use ($record) {
                            return $item->getKey() === $record->getKey();
                        });
                        
                        return $position !== false ? $position + 1 : null;
                    })
                    ->default('')
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('kodeKlasifikasi.kode_klasifikasi')
                    ->label('Kode Klasifikasi')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('kodeKlasifikasi.uraian')
                    ->label('Uraian Klasifikasi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->kodeKlasifikasi->uraian ?? '')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('indeks')
                    ->label('Indeks')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('jumlah_nilai')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('jumlah_satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([

            ])
            ->emptyStateHeading('Tidak ada unit arsip')
            ->emptyStateDescription('Belum ada unit arsip yang terkait dengan arsip aktif ini.')
            ->emptyStateIcon('heroicon-o-archive-box');
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('Lihat')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\TextInput::make('kodeKlasifikasi.kode_klasifikasi')
                        ->label('Kode Klasifikasi')
                        ->default(fn ($get, $record) => $record->kodeKlasifikasi?->kode_klasifikasi ?? 'Tidak ada')
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('indeks')
                        ->label('Indeks')
                        ->default(fn ($get, $record) => $record->indeks)
                        ->disabled(),
                    \Filament\Forms\Components\DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->default(fn ($get, $record) => $record->tanggal)
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('kategori.nama_kategori')
                        ->label('Kategori')
                        ->default(fn ($get, $record) => $record->kategori?->nama_kategori ?? 'Tidak ada')
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('subKategori.nama_sub_kategori')
                        ->label('Sub Kategori')
                        ->default(fn ($get, $record) => $record->subKategori?->nama_sub_kategori ?? 'Tidak ada')
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('jumlah_nilai')
                        ->label('Jumlah')
                        ->default(fn ($get, $record) => $record->jumlah_nilai . ' ' . $record->jumlah_satuan)
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('tingkat_perkembangan')
                        ->label('Tingkat Perkembangan')
                        ->default(fn ($get, $record) => $record->tingkat_perkembangan)
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('skkaad')
                        ->label('SKKAAD')
                        ->default(fn ($get, $record) => $record->skkaad)
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('status')
                        ->label('Status')
                        ->default(fn ($get, $record) => $record->status)
                        ->disabled(),
                    \Filament\Forms\Components\Textarea::make('uraian_informasi')
                        ->label('Uraian Informasi')
                        ->default(fn ($get, $record) => $record->uraian_informasi)
                        ->disabled()
                        ->columnSpanFull(),
                ])
                ->modalHeading(fn ($record) => 'Detail Arsip Unit: ' . ($record->indeks ?? 'N/A'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->visible(function ($record) {
                    /** @var \App\Models\User $user */
                    $user = \Illuminate\Support\Facades\Auth::user();
                    // View ditampilkan untuk user yang memiliki akses view
                    return $user->can('view', $record);
                }),
            Action::make('edit')
                ->label('Edit')
                ->icon('heroicon-o-pencil')
                ->color('warning')
                ->fillForm(fn ($record) => [
                    'kode_klasifikasi_id' => $record->kode_klasifikasi_id,
                    'indeks' => $record->indeks,
                    'tanggal' => $record->tanggal,
                    'uraian_informasi' => $record->uraian_informasi,
                    'jumlah_nilai' => $record->jumlah_nilai,
                    'jumlah_satuan' => $record->jumlah_satuan,
                    'tingkat_perkembangan' => $record->tingkat_perkembangan,
                    'retensi_aktif' => $record->retensi_aktif,
                    'retensi_inaktif' => $record->retensi_inaktif,
                    'skkaad' => $record->skkaad,
                    'ruangan' => $record->ruangan,
                    'no_filling' => $record->no_filling,
                    'no_laci' => $record->no_laci,
                    'no_folder' => $record->no_folder,
                    'no_box' => $record->no_box,
                    'kategori_id' => $record->kategori_id,
                    'sub_kategori_id' => $record->sub_kategori_id,
                    'status' => $record->status,
                    'keterangan' => $record->keterangan,
                ])
                ->form([
                    \Filament\Forms\Components\Select::make('kode_klasifikasi_id')
                        ->label('Kode Klasifikasi')
                        ->options(KodeKlasifikasi::all()->pluck('kode_klasifikasi', 'id_kode_klasifikasi'))
                        ->searchable(),
                    \Filament\Forms\Components\TextInput::make('indeks')
                        ->label('Indeks'),
                    \Filament\Forms\Components\DatePicker::make('tanggal')
                        ->label('Tanggal'),
                    \Filament\Forms\Components\Select::make('kategori_id')
                        ->label('Kategori')
                        ->options(Kategori::all()->pluck('nama_kategori', 'id_kategori'))
                        ->searchable(),
                    \Filament\Forms\Components\Select::make('sub_kategori_id')
                        ->label('Sub Kategori')
                        ->options(SubKategori::all()->pluck('nama_sub_kategori', 'id_sub_kategori'))
                        ->searchable(),
                    \Filament\Forms\Components\TextInput::make('jumlah_nilai')
                        ->label('Jumlah Nilai')
                        ->numeric(),
                    \Filament\Forms\Components\TextInput::make('jumlah_satuan')
                        ->label('Satuan'),
                    \Filament\Forms\Components\TextInput::make('tingkat_perkembangan')
                        ->label('Tingkat Perkembangan'),
                    \Filament\Forms\Components\TextInput::make('retensi_aktif')
                        ->label('Retensi Aktif')
                        ->numeric(),
                    \Filament\Forms\Components\TextInput::make('retensi_inaktif')
                        ->label('Retensi Inaktif')
                        ->numeric(),
                    \Filament\Forms\Components\TextInput::make('skkaad')
                        ->label('SKKAAD'),
                    \Filament\Forms\Components\TextInput::make('ruangan')
                        ->label('Ruangan'),
                    \Filament\Forms\Components\TextInput::make('no_filling')
                        ->label('No. Filling'),
                    \Filament\Forms\Components\TextInput::make('no_laci')
                        ->label('No. Laci'),
                    \Filament\Forms\Components\TextInput::make('no_folder')
                        ->label('No. Folder'),
                    \Filament\Forms\Components\TextInput::make('no_box')
                        ->label('No. Box'),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak',
                        ]),
                    \Filament\Forms\Components\TextInput::make('keterangan')
                        ->label('Keterangan'),
                    \Filament\Forms\Components\Textarea::make('uraian_informasi')
                        ->label('Uraian Informasi')
                        ->columnSpanFull(),
                ])
                ->action(function ($record, array $data) {
                    $record->update($data);
                })
                ->visible(function ($record) {
                    /** @var \App\Models\User $user */
                    $user = \Illuminate\Support\Facades\Auth::user();
                    // Edit ditampilkan untuk user yang memiliki akses update
                    return $user->can('update', $record);
                }),
        ];
    }
}
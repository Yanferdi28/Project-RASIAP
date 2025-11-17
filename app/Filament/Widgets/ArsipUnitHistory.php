<?php

namespace App\Filament\Widgets;

use App\Models\ArsipUnit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Action;

class ArsipUnitHistory extends BaseWidget
{
    protected static ?string $heading = 'History Pembuatan Arsip Unit';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';


    protected static ?string $pollingInterval = '10s';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25])
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('kodeKlasifikasi.kode_klasifikasi')
                    ->label('Kode Klasifikasi')
                    ->toggleable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('indeks')
                    ->label('Indeks')
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('uraian_informasi')
                    ->label('Uraian Informasi')
                    ->toggleable()
                    ->limit(30)
                    ->tooltip(fn ($state): ?string => strlen($state) > 30 ? $state : null),

                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->label('Tanggal')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jumlah_nilai')
                    ->label('Jumlah')
                    ->formatStateUsing(function ($record) {
                        return $record->jumlah_nilai . ' ' . $record->jumlah_satuan;
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tingkat_perkembangan')
                    ->label('Tingkat Perkembangan')
                    ->toggleable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('unitPengolah.nama_unit')
                    ->label('Unit Pengolah')
                    ->toggleable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('retensi_aktif')
                    ->label('Retensi Aktif')
                    ->toggleable()
                    ->numeric(),

                Tables\Columns\TextColumn::make('retensi_inaktif')
                    ->label('Retensi Inaktif')
                    ->toggleable()
                    ->numeric(),

                Tables\Columns\TextColumn::make('skkaad')
                    ->label('SKKAAD')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ruangan')
                    ->label('No Ruang')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('no_filling')
                    ->label('No Filling')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('no_laci')
                    ->label('No Laci')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('no_folder')
                    ->label('No Folder')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('no_box')
                    ->label('No Box')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->label('Dibuat')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('range_tanggal')
                    ->label('Rentang Tanggal Dibuat')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->url(fn (ArsipUnit $record) => route('filament.admin.resources.arsip-units.view', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->emptyStateHeading('Belum ada data')
            ->emptyStateDescription('Record Arsip Unit yang baru dibuat akan muncul di sini.');
    }

    protected function getQuery(): Builder
    {
        $query = ArsipUnit::query()
            ->with(['kodeKlasifikasi', 'kategori', 'subKategori', 'unitPengolah'])
            ->latest('created_at');

        $user = \Illuminate\Support\Facades\Auth::user();

        // Filter by user's unit if not admin, superadmin, or operator
        if (!$user->hasRole(['admin', 'superadmin', 'operator']) && $user->unit_pengolah_id) {
            $query->where('unit_pengolah_arsip_id', $user->unit_pengolah_id);
        }

        return $query;
    }
}

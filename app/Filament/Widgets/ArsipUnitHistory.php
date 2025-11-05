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

                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->label('Tanggal')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jumlah_satuan')
                    ->label('Jumlah Satuan')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tingkat_perkembangan')
                    ->label('Tingkat Perkembangan')
                    ->toggleable()
                    ->limit(20),

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
                Action::make('edit')
                    ->label('Edit')
                    ->url(fn (ArsipUnit $record) => route('filament.admin.resources.arsip-units.edit', $record))
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->emptyStateHeading('Belum ada data')
            ->emptyStateDescription('Record Arsip Unit yang baru dibuat akan muncul di sini.');
    }

    protected function getQuery(): Builder
    {
        return ArsipUnit::query()
            ->with(['kodeKlasifikasi'])
            ->latest('created_at');
    }
}

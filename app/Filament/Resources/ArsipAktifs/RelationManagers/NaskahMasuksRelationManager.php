<?php

namespace App\Filament\Resources\ArsipAktifs\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;

class NaskahMasuksRelationManager extends RelationManager
{
    protected static string $relationship = 'naskahMasuks';

    protected static ?string $title = 'Daftar Naskah Masuk';

    protected static ?string $recordTitleAttribute = 'nomor_naskah';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_naskah')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_naskah')
                    ->label('Nomor Naskah')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nomor naskah berhasil disalin!')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('tanggal_naskah')
                    ->label('Tanggal Naskah')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_diterima')
                    ->label('Tanggal Diterima')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('hal')
                    ->label('Perihal')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn ($state): ?string => strlen($state) > 50 ? $state : null)
                    ->wrap(),

                Tables\Columns\TextColumn::make('nama_pengirim')
                    ->label('Pengirim')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('instansi_pengirim')
                    ->label('Instansi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('jenis_naskah')
                    ->label('Jenis')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sifat_naskah')
                    ->label('Sifat')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Segera' => 'danger',
                        'Penting' => 'warning',
                        'Biasa' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_naskah')
                    ->label('Jenis Naskah')
                    ->multiple(),

                Tables\Filters\SelectFilter::make('sifat_naskah')
                    ->label('Sifat Naskah')
                    ->options([
                        'Segera' => 'Segera',
                        'Penting' => 'Penting',
                        'Biasa' => 'Biasa',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('tanggal_naskah')
                    ->schema([
                        Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_naskah', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_naskah', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['dari'])->format('d/m/Y');
                        }
                        if ($data['sampai'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['sampai'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),
            ])
            ->emptyStateHeading('Belum ada naskah')
            ->emptyStateDescription('Belum ada naskah yang dimasukkan ke berkas ini.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->defaultSort('tanggal_naskah', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('detach')
                ->label('Keluarkan')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Keluarkan Naskah dari Berkas')
                ->modalDescription('Naskah akan dikeluarkan dari berkas ini. Naskah tidak akan dihapus.')
                ->modalSubmitActionLabel('Ya, Keluarkan')
                ->action(function ($record) {
                    $record->arsip_aktif_id = null;
                    $record->save();
                    
                    Notification::make()
                        ->title('Berhasil!')
                        ->body('Naskah berhasil dikeluarkan dari berkas.')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('detach_bulk')
                ->label('Keluarkan dari Berkas')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Keluarkan Naskah dari Berkas')
                ->modalDescription('Naskah-naskah terpilih akan dikeluarkan dari berkas ini.')
                ->action(function ($records) {
                    $records->each(function ($record) {
                        $record->arsip_aktif_id = null;
                        $record->save();
                    });
                    
                    Notification::make()
                        ->title('Berhasil!')
                        ->body('Naskah berhasil dikeluarkan dari berkas.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
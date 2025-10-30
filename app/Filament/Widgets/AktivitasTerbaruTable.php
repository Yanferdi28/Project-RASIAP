<?php

namespace App\Filament\Widgets;

use App\Models\NaskahMasuk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AktivitasTerbaruTable extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(NaskahMasuk::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('nomor_naskah')->label('Nomor')->searchable(),
                Tables\Columns\TextColumn::make('nama_pengirim')->label('Pengirim')->toggleable(),
                Tables\Columns\TextColumn::make('tanggal_diterima')->label('Diterima')->date(),
                Tables\Columns\TextColumn::make('jenis_naskah')->label('Jenis')->badge(),
            ])
            ->paginated(10);
    }
}

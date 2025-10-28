<?php

namespace App\Filament\Resources\NaskahMasuks;

use App\Filament\Resources\NaskahMasuks\Schemas\NaskahMasukForm;
use App\Filament\Resources\NaskahMasuks\Tables\NaskahMasuksTable;
use App\Models\NaskahMasuk;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum; // PENTING: Pastikan UnitEnum diimport
use BackedEnum;

class NaskahMasukResource extends Resource
{
    protected static ?string $model = NaskahMasuk::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'Naskah Masuk';

    protected static ?string $navigationLabel = 'Naskah Masuk';

    protected static ?string $modelLabel = 'Naskah Masuk';
    
    protected static ?string $pluralLabel = 'Naskah Masuk';
    
    protected static string | UnitEnum | null $navigationGroup = 'Kegiatan Arsip';

    public static function form(Schema $schema): Schema
    {
        return NaskahMasukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NaskahMasuksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            // Pastikan Anda membuat file pages ini (ListNaskahMasuks, CreateNaskahMasuk, EditNaskahMasuk)
            'index' => Pages\ListNaskahMasuks::route('/'),
            'create' => Pages\CreateNaskahMasuk::route('/create'),
            'edit' => Pages\EditNaskahMasuk::route('/{record}/edit'),
        ];
    }
}
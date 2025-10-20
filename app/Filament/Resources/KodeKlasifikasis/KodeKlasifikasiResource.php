<?php

namespace App\Filament\Resources\KodeKlasifikasis;

use App\Filament\Resources\KodeKlasifikasis\Pages\CreateKodeKlasifikasi;
use App\Filament\Resources\KodeKlasifikasis\Pages\EditKodeKlasifikasi;
use App\Filament\Resources\KodeKlasifikasis\Pages\ListKodeKlasifikasis;
use App\Filament\Resources\KodeKlasifikasis\Schemas\KodeKlasifikasiForm;
use App\Filament\Resources\KodeKlasifikasis\Tables\KodeKlasifikasisTable;
use App\Models\KodeKlasifikasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KodeKlasifikasiResource extends Resource
{
    protected static ?string $model = KodeKlasifikasi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Kode Klasifikasi';

    protected static ?string $navigationLabel = 'Kode Klasifikasi';

    protected static ?string $modelLabel = 'Kode Klasifikasi';
    
    protected static ?string $pluralLabel = 'Kode Klasifikasi';

    protected static string | UnitEnum | null $navigationGroup = 'Master';

    public static function form(Schema $schema): Schema
    {
        return KodeKlasifikasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KodeKlasifikasisTable::configure($table);
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
            'index' => ListKodeKlasifikasis::route('/'),
            'create' => CreateKodeKlasifikasi::route('/create'),
            'edit' => EditKodeKlasifikasi::route('/{record}/edit'),
        ];
    }
}

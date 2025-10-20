<?php

namespace App\Filament\Resources\UnitPengolahs;

use App\Filament\Resources\UnitPengolahs\Pages\CreateUnitPengolah;
use App\Filament\Resources\UnitPengolahs\Pages\EditUnitPengolah;
use App\Filament\Resources\UnitPengolahs\Pages\ListUnitPengolahs;
use App\Filament\Resources\UnitPengolahs\Schemas\UnitPengolahForm;
use App\Filament\Resources\UnitPengolahs\Tables\UnitPengolahsTable;
use App\Models\UnitPengolah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class UnitPengolahResource extends Resource
{
    protected static ?string $model = UnitPengolah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Unit Pengolah';

    protected static ?string $navigationLabel = 'Unit Pengolah';

    protected static ?string $modelLabel = 'Unit Pengolah';
    
    protected static ?string $pluralLabel = 'Unit Pengolah';

    protected static string | UnitEnum | null $navigationGroup = 'Master';

    public static function form(Schema $schema): Schema
    {
        return UnitPengolahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitPengolahsTable::configure($table);
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
            'index' => ListUnitPengolahs::route('/'),
            'create' => CreateUnitPengolah::route('/create'),
            'edit' => EditUnitPengolah::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ArsipUnits;

use App\Filament\Resources\ArsipUnits\Pages\CreateArsipUnit;
use App\Filament\Resources\ArsipUnits\Pages\EditArsipUnit;
use App\Filament\Resources\ArsipUnits\Pages\ListArsipUnits;
use App\Filament\Resources\ArsipUnits\Pages\ViewArsipUnit;
use App\Filament\Resources\ArsipUnits\Schemas\ArsipUnitForm;
use App\Filament\Resources\ArsipUnits\Tables\ArsipUnitsTable;
use App\Models\ArsipUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use UnitEnum;

class ArsipUnitResource extends Resource
{
    protected static ?string $model = ArsipUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBoxArrowDown;

    protected static ?string $recordTitleAttribute = 'Arsip Unit';

    protected static ?string $navigationLabel = 'Arsip Unit';

    protected static ?string $modelLabel = 'Arsip Unit';
    
    protected static ?string $pluralLabel = 'Arsip Unit';

    protected static string | UnitEnum | null $navigationGroup = 'Daftar Arsip';

    public static function form(Schema $schema): Schema
    {
        return ArsipUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArsipUnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \Illuminate\Support\Facades\Gate::allows('viewAny', ArsipUnit::class);
    }
    

    public static function getPages(): array
    {
        return [
            'index' => ListArsipUnits::route('/'),
            'create' => CreateArsipUnit::route('/create'),
            'edit' => EditArsipUnit::route('/{record}/edit'),
            'view' => ViewArsipUnit::route('/{record}'),
        ];
    }
}

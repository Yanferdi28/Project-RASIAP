<?php

namespace App\Filament\Resources\ArsipInaktifs;

use App\Filament\Resources\ArsipInaktifs\Pages\CreateArsipInaktif;
use App\Filament\Resources\ArsipInaktifs\Pages\EditArsipInaktif;
use App\Filament\Resources\ArsipInaktifs\Pages\ListArsipInaktifs;
use App\Filament\Resources\ArsipInaktifs\Schemas\ArsipInaktifForm;
use App\Filament\Resources\ArsipInaktifs\Tables\ArsipInaktifsTable;
use App\Models\ArsipInaktif as ModelsArsipInaktif;
use ArsipInaktif;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ArsipInaktifResource extends Resource
{
    protected static ?string $model = ModelsArsipInaktif::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownOnSquare;

    protected static ?string $recordTitleAttribute = 'Arsip Inaktif';

    protected static ?string $navigationLabel = 'Pemberkasan Arsip Inaktif';

    protected static ?string $modelLabel = 'Pemberkasan Arsip Inaktif';
    
    protected static ?string $pluralLabel = 'Pemberkasan Arsip Inaktif';

    protected static string | UnitEnum | null $navigationGroup = 'Pemeliharaan Arsip';

    public static function form(Schema $schema): Schema
    {
        return ArsipInaktifForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArsipInaktifsTable::configure($table);
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
            'index' => ListArsipInaktifs::route('/'),
            'create' => CreateArsipInaktif::route('/create'),
            'edit' => EditArsipInaktif::route('/{record}/edit'),
        ];
    }
}

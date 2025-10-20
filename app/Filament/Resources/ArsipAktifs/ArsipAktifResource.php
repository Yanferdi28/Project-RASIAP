<?php

namespace App\Filament\Resources\ArsipAktifs;

use App\Filament\Resources\ArsipAktifs\Pages\CreateArsipAktif;
use App\Filament\Resources\ArsipAktifs\Pages\EditArsipAktif;
use App\Filament\Resources\ArsipAktifs\Pages\ListArsipAktifs;
use App\Filament\Resources\ArsipAktifs\Schemas\ArsipAktifForm;
use App\Filament\Resources\ArsipAktifs\Tables\ArsipAktifsTable;
use App\Models\ArsipAktif;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ArsipAktifResource extends Resource
{
    protected static ?string $model = ArsipAktif::class;

    protected static ?string $recordTitleAttribute = 'Arsip Aktif';

    protected static ?string $navigationLabel = 'Pemberkasan Arsip Aktif';

    protected static ?string $modelLabel = 'Pemberkasan Arsip Aktif';
    
    protected static ?string $pluralLabel = 'Pemberkasan Arsip Aktif';
    
    protected static string | UnitEnum | null $navigationGroup = 'Pemeliharaan Arsip';

    public static function form(Schema $schema): Schema
    {
        return ArsipAktifForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArsipAktifsTable::configure($table);
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
            'index' => ListArsipAktifs::route('/'),
            'create' => CreateArsipAktif::route('/create'),
            'edit' => EditArsipAktif::route('/{record}/edit'),
        ];
    }
}

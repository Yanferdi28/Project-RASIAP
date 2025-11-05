<?php

namespace App\Filament\Resources\SubKategoris;

use App\Filament\Resources\SubKategoris\Pages\CreateSubKategori;
use App\Filament\Resources\SubKategoris\Pages\EditSubKategori;
use App\Filament\Resources\SubKategoris\Pages\ListSubKategoris;
use App\Filament\Resources\SubKategoris\Schemas\SubKategoriForm;
use App\Filament\Resources\SubKategoris\Tables\SubKategorisTable;
use App\Models\SubKategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SubKategoriResource extends Resource
{
    protected static ?string $model = SubKategori::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'nama_sub_kategori';

    protected static ?string $navigationLabel = 'Sub Kategori Informasi';

    protected static ?string $modelLabel = 'Sub Kategori';
    
    protected static ?string $pluralLabel = 'Sub Kategori';

    protected static string | UnitEnum | null $navigationGroup = 'Kategori';

    public static function form(Schema $schema): Schema
    {
        return SubKategoriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubKategorisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubKategoris::route('/'),
            'create' => CreateSubKategori::route('/create'),
            'edit' => EditSubKategori::route('/{record}/edit'),
        ];
    }
}
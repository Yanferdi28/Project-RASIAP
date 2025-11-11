<?php

namespace App\Filament\Resources\BerkasArsips;

use App\Filament\Resources\BerkasArsips\Pages\CreateBerkasArsip;
use App\Filament\Resources\BerkasArsips\Pages\EditBerkasArsip;
use App\Filament\Resources\BerkasArsips\Pages\ListBerkasArsips;
use App\Filament\Resources\BerkasArsips\Pages\ViewBerkasArsip;
use App\Filament\Resources\BerkasArsips\Schemas\BerkasArsipForm;
use App\Filament\Resources\BerkasArsips\Tables\BerkasArsipsTable;
use App\Filament\Exports\BerkasArsipExporter;
use Filament\Support\Icons\Heroicon;
use App\Models\BerkasArsip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class BerkasArsipResource extends Resource
{
    protected static ?string $model = BerkasArsip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpOnSquare;

    protected static ?string $recordTitleAttribute = 'nama_berkas';

    protected static ?string $navigationLabel = 'Pemberkasan Berkas Arsip';

    protected static ?string $modelLabel = 'Pemberkasan Berkas Arsip';

    protected static ?string $pluralLabel = 'Pemberkasan Berkas Arsip';

    protected static string | UnitEnum | null $navigationGroup = 'Pemeliharaan Arsip';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Hanya admin, user, atau operator yang bisa mengakses resource ini
        return $user->hasAnyRole(['admin', 'user', 'operator']);
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Hanya admin, user, atau operator yang bisa melihat daftar resource
        return $user->can('viewAny', static::$model);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Hanya admin dan user yang bisa membuat resource baru
        return $user->can('create', static::$model);
    }

    public static function form(Schema $schema): Schema
    {
        return BerkasArsipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BerkasArsipsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\BerkasArsips\RelationManagers\BerkasArsipUnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBerkasArsips::route('/'),
            'create' => CreateBerkasArsip::route('/create'),
            'view' => ViewBerkasArsip::route('/{record}'),
            'edit' => EditBerkasArsip::route('/{record}/edit'),
        ];
    }
}
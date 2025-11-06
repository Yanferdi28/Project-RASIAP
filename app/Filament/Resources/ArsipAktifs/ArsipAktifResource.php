<?php

namespace App\Filament\Resources\ArsipAktifs;

use App\Filament\Resources\ArsipAktifs\Pages\CreateArsipAktif;
use App\Filament\Resources\ArsipAktifs\Pages\EditArsipAktif;
use App\Filament\Resources\ArsipAktifs\Pages\ListArsipAktifs;
use App\Filament\Resources\ArsipAktifs\Pages\ViewArsipAktif;
use App\Filament\Resources\ArsipAktifs\Schemas\ArsipAktifForm;
use App\Filament\Resources\ArsipAktifs\Tables\ArsipAktifsTable;
use App\Filament\Exports\ArsipAktifExporter;
use Filament\Support\Icons\Heroicon;
use App\Models\ArsipAktif;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ArsipAktifResource extends Resource
{
    protected static ?string $model = ArsipAktif::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpOnSquare;

    protected static ?string $recordTitleAttribute = 'nama_berkas';

    protected static ?string $navigationLabel = 'Pemberkasan Arsip Aktif';

    protected static ?string $modelLabel = 'Pemberkasan Arsip Aktif';
    
    protected static ?string $pluralLabel = 'Pemberkasan Arsip Aktif';
    
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
        return ArsipAktifForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArsipAktifsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\ArsipAktifs\RelationManagers\ArsipUnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArsipAktifs::route('/'),
            'create' => CreateArsipAktif::route('/create'),
            'view' => ViewArsipAktif::route('/{record}'),
            'edit' => EditArsipAktif::route('/{record}/edit'),
        ];
    }
}
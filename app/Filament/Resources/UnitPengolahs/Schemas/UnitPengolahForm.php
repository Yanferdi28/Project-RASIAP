<?php

namespace App\Filament\Resources\UnitPengolahs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UnitPengolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_unit')
                    ->required(),
            ]);
    }
}

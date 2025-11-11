<?php

namespace App\Filament\Widgets;

use App\Models\BerkasArsip;
use App\Models\ArsipUnit;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 1;

    protected function getStats(): array

    {
        return [

            Stat::make('Jumlah Pemberkasan Unit Berkas', ArsipUnit::count())
                ->description('Total pemberkasan unit berkas yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),

            Stat::make('Jumlah Pemberkasan Berkas Arsip', BerkasArsip::count())
                ->description('Total pemberkasan berkas arsip yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),

        ];
    }
}
<?php

namespace App\Filament\Widgets;

use App\Models\ArsipAktif;
use App\Models\ArsipInaktif;
use App\Models\ArsipUnit;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 1;

    protected function getStats(): array
    
    {
        return [

            Stat::make('Jumlah Pemberkasan Arsip Unit', ArsipUnit::count())
                ->description('Total pemberkasan arsip unit yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),

            Stat::make('Jumlah Pemberkasan Arsip Aktif', ArsipAktif::count())
                ->description('Total pemberkasan arsip aktif yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),
                
            Stat::make('Jumlah Pemberkasan Arsip Inaktif', ArsipInaktif::count())
                ->description('Total pemberkasan arsip inaktif yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),
        ];
    }
}
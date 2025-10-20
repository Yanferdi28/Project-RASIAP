<?php

namespace App\Filament\Widgets;

use App\Models\ArsipAktif;
use App\Models\ArsipInaktif;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Arsip Aktif', ArsipAktif::count())
                ->description('Total arsip aktif yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('success'),
                
            Stat::make('Jumlah Arsip Inaktif', ArsipInaktif::count())
                ->description('Total arsip inaktif yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),
        ];
    }
}
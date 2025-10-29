<?php

namespace App\Filament\Widgets;

use App\Models\ArsipAktif;
use App\Models\ArsipInaktif;
use App\Models\NaskahMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Naskah Masuk', NaskahMasuk::count())
                ->description('Total naskah masuk yang tersimpan')
                ->icon('heroicon-o-document-duplicate')
                ->color('info'),
                
            Stat::make('Jumlah Arsip Aktif', ArsipAktif::count())
                ->description('Total arsip aktif yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),
                
            Stat::make('Jumlah Arsip Inaktif', ArsipInaktif::count())
                ->description('Total arsip inaktif yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),
        ];
    }
}
<?php

namespace App\Filament\Widgets;

use App\Models\ArsipUnit;
use App\Models\KodeKlasifikasi;
use App\Models\UnitPengolah;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Arsip Unit', ArsipUnit::count())
                ->description('Total arsip unit yang tersimpan')
                ->icon('heroicon-o-document-duplicate')
                ->color('success'),
                
            Stat::make('Jumlah Kode Klasifikasi', KodeKlasifikasi::count())
                ->description('Total kode klasifikasi')
                ->icon('heroicon-o-tag')
                ->color('info'),

            Stat::make('Jumlah Unit Pengolah', UnitPengolah::count())
                ->description('Total unit pengolah arsip')
                ->icon('heroicon-o-building-office')
                ->color('warning'),

            Stat::make('Jumlah User', User::count())
                ->description('Total pengguna terdaftar')
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
<?php

namespace App\Filament\Widgets;

use App\Models\BerkasArsip;
use App\Models\ArsipUnit;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 1;

    protected function getStats(): array

    {
        $user = Auth::user();
        $arsipUnitQuery = ArsipUnit::query();

        // Filter by user's unit if not admin, superadmin, or operator
        if (!$user->hasRole(['admin', 'superadmin', 'operator']) && $user->unit_pengolah_id) {
            $arsipUnitQuery->where('unit_pengolah_arsip_id', $user->unit_pengolah_id);
        }

        // Operator can only see records with assigned category (not null or "-")
        if ($user->hasRole('operator')) {
            $arsipUnitQuery->whereNotNull('kategori_id')
                           ->where('kategori_id', '!=', '')
                           ->whereHas('kategori', function($q) {
                               $q->where('nama_kategori', '!=', '-')
                                 ->where('nama_kategori', '!=', '');
                           });
        }

        return [

            Stat::make('Jumlah Pemberkasan Unit Berkas', $arsipUnitQuery->count())
                ->description('Total pemberkasan unit berkas yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),

            Stat::make('Jumlah Pemberkasan Berkas Arsip', BerkasArsip::count())
                ->description('Total pemberkasan berkas arsip yang tersimpan')
                ->icon('heroicon-o-archive-box')
                ->color('info'),

            Stat::make('Jumlah Pengguna', User::count())
                ->description('Total pengguna yang terdaftar')
                ->icon('heroicon-o-user-group')
                ->color('info'),

        ];
    }
}
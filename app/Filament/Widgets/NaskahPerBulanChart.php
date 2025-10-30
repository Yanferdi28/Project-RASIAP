<?php

namespace App\Filament\Widgets;

use App\Models\NaskahMasuk;
use Filament\Widgets\ChartWidget;

class NaskahPerBulanChart extends ChartWidget
{
    // GANTI: dari "protected static ?string $heading" -> non-static
    protected ?string $heading = 'Naskah Masuk per Bulan (12 bln)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $rows = NaskahMasuk::selectRaw("DATE_FORMAT(tanggal_diterima, '%Y-%m') AS ym, COUNT(*) AS total")
            ->groupBy('ym')
            ->orderBy('ym')
            ->limit(12)
            ->get();

        return [
            'datasets' => [
                ['label' => 'Naskah', 'data' => $rows->pluck('total')->toArray()],
            ],
            'labels' => $rows->pluck('ym')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

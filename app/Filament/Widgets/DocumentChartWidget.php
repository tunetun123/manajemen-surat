<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Document;
use Carbon\Carbon;

class DocumentChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Penambahan Dokumen per Bulan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->translatedFormat('M Y');
            $data[] = Document::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Dokumen Ditambahkan',
                    'data' => $data,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\DocumentType;

class DocumentTypeChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Komposisi Jenis Dokumen';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $types = DocumentType::withCount('documents')->get();
        return [
            'datasets' => [
                [
                    'label' => 'Total Dokumen',
                    'data' => $types->pluck('documents_count')->toArray(),
                    'backgroundColor' => ['#696cff', '#71dd37', '#ffab00', '#ff3e1d', '#03c3ec', '#8592a3'],
                ],
            ],
            'labels' => $types->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Document;
use App\Models\Category;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Dokumen', Document::count())
                ->description('Total berkas digital')
                ->descriptionIcon('fas-file-contract')
                ->color('primary'),
            Stat::make('Kategori', Category::count())
                ->description('Kategori dokumen')
                ->descriptionIcon('fas-folder')
                ->color('success'),
            Stat::make('Pengguna Aktif', User::count())
                ->description('Jumlah pengguna aplikasi')
                ->descriptionIcon('fas-users')
                ->color('warning'),
        ];
    }
}

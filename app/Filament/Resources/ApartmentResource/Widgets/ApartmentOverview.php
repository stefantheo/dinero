<?php

namespace App\Filament\Resources\ApartmentResource\Widgets;

use App\Filament\Resources\ApartmentResource\Pages\ListApartments;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApartmentOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    use InteractsWithPageTable;
    protected function getTablePage(): string
    {
        return ListApartments::class;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total apartamente',$this->getPageTableQuery()->count()),
            Stat::make('Total adulti',$this->getPageTableQuery()->sum('adult_number')),
            Stat::make('Total copii',$this->getPageTableQuery()->sum('children_number')),
        ];
    }
    protected function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }
}

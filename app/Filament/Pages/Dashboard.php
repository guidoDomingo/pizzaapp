<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Centro de Operaciones';
    protected static ?string $title = '🎯 Centro de Operaciones';
    protected static ?int $navigationSort = 1;

    protected function getWidgets(): array
    {
        return [
            \App\Filament\Resources\PizzaResource\Widgets\StatsOverview::class,
            \App\Filament\Widgets\OrdersChart::class,
            \App\Filament\Resources\PizzaResource\Widgets\ActiveOrdersTable::class,
        ];
    }
    
    protected function getColumns(): int | array
    {
        return 12;
    }
}

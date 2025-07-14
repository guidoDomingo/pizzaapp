<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\OrderManagementWidget::class,
        ];
    }
    
    protected function getColumns(): int | array
    {
        return 1;
    }
}

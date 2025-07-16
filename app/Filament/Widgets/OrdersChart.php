<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\BarChartWidget;
use Carbon\Carbon;

class OrdersChart extends BarChartWidget
{
    protected static ?string $heading = 'Órdenes por Día (Última Semana)';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        // Obtener datos de los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M j');
            $data[] = Order::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Órdenes',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.1)',
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }
}

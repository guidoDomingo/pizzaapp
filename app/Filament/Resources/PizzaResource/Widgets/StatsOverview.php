<?php

namespace App\Filament\Resources\PizzaResource\Widgets;

use App\Models\Order;
use App\Models\Table;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        // Obtener estadísticas
        $pendingOrders = Order::where('status', 'pending')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        $readyOrders = Order::where('status', 'ready')->count();
        $deliveringOrders = Order::where('status', 'delivering')->count();
        $dailySales = Order::whereDate('created_at', today())
            ->whereNotNull('paid_at')
            ->sum('total') ?? 0;
        $availableTables = Table::where('status', 'available')->count();
        $totalTables = Table::count();

        return [
            Card::make('Órdenes Pendientes', $pendingOrders)
                ->description('Esperando cocina')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
                
            Card::make('En Preparación', $preparingOrders)
                ->description('En cocina')
                ->descriptionIcon('heroicon-o-lightning-bolt')
                ->color('primary'),
                
            Card::make('Listas para Servir', $readyOrders)
                ->description('Para entregar')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
                
            Card::make('Entregando', $deliveringOrders)
                ->description('En camino')
                ->descriptionIcon('heroicon-o-truck')
                ->color('secondary'),
                
            Card::make('Ventas del Día', '$' . number_format($dailySales, 0))
                ->description('Total generado hoy')
                ->descriptionIcon('heroicon-o-cash')
                ->color('success'),
                
            Card::make('Mesas Disponibles', $availableTables . '/' . $totalTables)
                ->description($availableTables . ' mesas libres')
                ->descriptionIcon('heroicon-o-view-grid')
                ->color($availableTables > 0 ? 'success' : 'danger'),
        ];
    }
}

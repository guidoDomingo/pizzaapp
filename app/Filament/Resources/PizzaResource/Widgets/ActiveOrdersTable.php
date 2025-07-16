<?php

namespace App\Filament\Resources\PizzaResource\Widgets;

use App\Models\Order;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ActiveOrdersTable extends BaseWidget
{
    protected static ?string $heading = 'Órdenes Activas';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Order::query()
            ->whereIn('status', ['pending', 'preparing', 'ready', 'delivering'])
            ->with(['customer', 'table'])
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
                
            Tables\Columns\TextColumn::make('order_number')
                ->label('Número de Orden')
                ->searchable()
                ->sortable(),
                
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Cliente')
                ->searchable()
                ->default('Cliente General'),
                
            Tables\Columns\TextColumn::make('table.name')
                ->label('Mesa')
                ->sortable()
                ->default('Sin mesa'),
                
            Tables\Columns\BadgeColumn::make('status')
                ->label('Estado')
                ->enum([
                    'pending' => 'Pendiente',
                    'preparing' => 'Preparando',
                    'ready' => 'Lista',
                    'delivering' => 'Entregando',
                ])
                ->colors([
                    'warning' => 'pending',
                    'primary' => 'preparing',
                    'success' => 'ready',
                    'secondary' => 'delivering',
                ]),
                
            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->money('usd')
                ->sortable(),
                
            Tables\Columns\TextColumn::make('created_at')
                ->label('Creada')
                ->dateTime()
                ->sortable(),
        ];
    }
    
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->label('Ver')
                ->icon('heroicon-o-eye')
                ->url(fn (Order $record): string => "/admin/orders/{$record->id}")
                ->openUrlInNewTab(),
        ];
    }
}

<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class KitchenBoard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-fire';
    
    protected static ?string $navigationGroup = 'Operaciones';
    
    protected static ?int $navigationSort = 1;
    
    protected static string $view = 'filament.pages.kitchen-board';
    
    protected static ?string $title = 'Tablero de Cocina';
    
    protected static ?string $navigationLabel = 'Tablero de Cocina';

    protected function getTableQuery(): Builder
    {
        return Order::query()
            ->whereIn('status', ['pending', 'preparing'])
            ->with(['customer', 'table', 'orderItems.product'])
            ->orderBy('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('order_number')
                ->label('# Orden')
                ->size('lg')
                ->weight('bold')
                ->color('primary'),
                
            Tables\Columns\BadgeColumn::make('table.number')
                ->label('Mesa')
                ->getStateUsing(fn ($record) => $record->table?->number ?? 'Sin mesa')
                ->color('secondary'),
                
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Cliente')
                ->default('Cliente General'),
                
            Tables\Columns\BadgeColumn::make('status')
                ->label('Estado')
                ->enum([
                    'pending' => 'Pendiente',
                    'preparing' => 'Preparando',
                ])
                ->colors([
                    'warning' => 'pending',
                    'primary' => 'preparing',
                ]),
                
            Tables\Columns\TextColumn::make('orderItems')
                ->label('Productos')
                ->formatStateUsing(function ($record) {
                    return $record->orderItems
                        ->map(fn($item) => "{$item->quantity}x {$item->product->name}")
                        ->join(', ');
                })
                ->wrap(),
                
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tiempo')
                ->since()
                ->color(fn($record) => $record->created_at->diffInMinutes() > 30 ? 'danger' : 'success'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('start_cooking')
                ->label('Comenzar')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->visible(fn (Order $record): bool => $record->status === 'pending')
                ->action(function (Order $record): void {
                    $record->update(['status' => 'preparing']);
                    $this->notify('success', 'Orden enviada a cocina');
                }),
                
            Tables\Actions\Action::make('mark_ready')
                ->label('Listo')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn (Order $record): bool => $record->status === 'preparing')
                ->action(function (Order $record): void {
                    $record->update(['status' => 'ready']);
                    $this->notify('success', 'Orden lista para servir');
                }),
        ];
    }

    protected function getTablePollingInterval(): ?string
    {
        return '10s'; // Actualizar cada 10 segundos
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No hay órdenes pendientes';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Todas las órdenes están completadas o no hay órdenes nuevas.';
    }
}

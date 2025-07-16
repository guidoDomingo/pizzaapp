<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Table;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class WaiterBoard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Operaciones';
    
    protected static ?int $navigationSort = 2;
    
    protected static string $view = 'filament.pages.waiter-board';
    
    protected static ?string $title = 'Panel de Meseros';
    
    protected static ?string $navigationLabel = 'Panel de Meseros';

    public function getTableQuery(): Builder
    {
        return Order::query()
            ->whereIn('status', ['ready', 'delivering'])
            ->with(['customer', 'table', 'orderItems.product'])
            ->orderBy('updated_at');
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
                ->getStateUsing(fn ($record) => $record->table?->number ?? 'Para llevar')
                ->color('secondary'),
                
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Cliente')
                ->default('Cliente General'),
                
            Tables\Columns\BadgeColumn::make('status')
                ->label('Estado')
                ->enum([
                    'ready' => 'Listo',
                    'delivering' => 'Entregando',
                ])
                ->colors([
                    'success' => 'ready',
                    'primary' => 'delivering',
                ]),
                
            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->money('usd')
                ->size('lg')
                ->weight('bold'),
                
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Actualizado')
                ->since(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('deliver')
                ->label('Entregar')
                ->icon('heroicon-o-truck')
                ->color('primary')
                ->visible(fn (Order $record): bool => $record->status === 'ready')
                ->action(function (Order $record): void {
                    $record->update(['status' => 'delivering']);
                    $this->notify('success', 'Orden en camino a la mesa');
                }),
                
            Tables\Actions\Action::make('complete')
                ->label('Completar')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (Order $record): bool => $record->status === 'delivering')
                ->action(function (Order $record): void {
                    $record->update([
                        'status' => 'completed',
                        'delivered_at' => now()
                    ]);
                    
                    // Liberar mesa si existe
                    if ($record->table) {
                        $record->table->update(['status' => 'available']);
                    }
                    
                    $this->notify('success', 'Orden completada exitosamente');
                }),
                
            Tables\Actions\Action::make('view_details')
                ->label('Ver Detalles')
                ->icon('heroicon-o-eye')
                ->color('secondary')
                ->modalContent(function (Order $record): string {
                    $items = $record->orderItems->map(function ($item) {
                        return "• {$item->quantity}x {$item->product->name} - $" . number_format($item->price * $item->quantity, 2);
                    })->join('<br>');
                    
                    $tableName = $record->table ? $record->table->number : 'Para llevar';
                    $customerName = $record->customer ? $record->customer->name : 'Cliente General';
                    
                    return "
                        <div class='space-y-4'>
                            <div>
                                <h4 class='font-semibold'>Orden: {$record->order_number}</h4>
                                <p class='text-sm text-gray-600'>Mesa: {$tableName}</p>
                                <p class='text-sm text-gray-600'>Cliente: {$customerName}</p>
                            </div>
                            <div>
                                <h5 class='font-medium'>Productos:</h5>
                                <div class='text-sm'>{$items}</div>
                            </div>
                            <div class='border-t pt-2'>
                                <p class='font-semibold'>Total: $" . number_format($record->total, 2) . "</p>
                            </div>
                        </div>
                    ";
                }),
        ];
    }

    protected function getTablePollingInterval(): ?string
    {
        return '15s'; // Actualizar cada 15 segundos
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No hay órdenes listas';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Las órdenes aparecerán aquí cuando estén listas para servir.';
    }

    public function getTablesStats(): array
    {
        return [
            'occupied' => Table::where('status', 'occupied')->count(),
            'available' => Table::where('status', 'available')->count(),
            'total' => Table::count(),
        ];
    }
}

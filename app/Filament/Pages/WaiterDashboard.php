<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Order;

class WaiterDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static string $view = 'filament.pages.waiter-dashboard';
    
    protected static ?string $navigationLabel = 'Tablero de Meseros';
    
    protected static ?string $title = '🍽️ Tablero de Meseros';
    
    protected static ?string $navigationGroup = 'Operaciones';
    
    protected static ?int $navigationSort = 2;

    public function mount(): void
    {
        // Verificar permisos si es necesario
    }

    public function getReadyOrders()
    {
        return Order::with(['table', 'items.product'])
            ->where('status', 'ready')
            ->orderBy('updated_at', 'asc')
            ->get();
    }

    public function getDeliveringOrders()
    {
        return Order::with(['table', 'items.product'])
            ->where('status', 'delivering')
            ->orderBy('updated_at', 'asc')
            ->get();
    }

    public function startDelivering($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'ready') {
            $order->update([
                'status' => 'delivering',
                'employee_id' => auth()->id()
            ]);
            
            $this->notify('success', '¡Pedido en entrega!');
        }
    }

    public function markDelivered($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'delivering') {
            $order->update(['status' => 'delivered']);
            
            // Liberar la mesa
            if ($order->table) {
                $order->table->update(['status' => 'available']);
            }
            
            $this->notify('success', '¡Pedido entregado! Mesa liberada.');
        }
    }
}

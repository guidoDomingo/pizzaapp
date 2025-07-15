<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Order;
use Livewire\Component;

class KitchenDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static string $view = 'filament.pages.kitchen-dashboard';
    
    protected static ?string $navigationLabel = 'Tablero de Cocina';
    
    protected static ?string $title = '👨‍🍳 Tablero de Cocina';
    
    protected static ?string $navigationGroup = 'Operaciones';
    
    protected static ?int $navigationSort = 1;

    public function mount(): void
    {
        // Verificar permisos si es necesario
    }

    public function getPendingOrders()
    {
        return Order::with(['table', 'items.product'])
            ->where('status', 'pending')
            ->whereNotNull('paid_at') // Solo órdenes pagadas
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getPreparingOrders()
    {
        return Order::with(['table', 'items.product'])
            ->where('status', 'preparing')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function startPreparing($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'pending') {
            $order->update([
                'status' => 'preparing',
                'employee_id' => auth()->id()
            ]);
            
            $this->notify('success', '¡Pedido en preparación!');
        }
    }

    public function markReady($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'preparing') {
            $order->update(['status' => 'ready']);
            
            $this->notify('success', '¡Pedido listo para entregar!');
        }
    }
}

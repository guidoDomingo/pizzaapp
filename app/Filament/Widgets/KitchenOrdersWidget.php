<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;
use App\Models\OrderItem;

class KitchenOrdersWidget extends Widget
{
    protected static string $view = 'filament.widgets.kitchen-orders-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;
    
    protected static ?string $heading = '👨‍🍳 Tablero de Cocina';

    public function getViewData(): array
    {
        return [
            'pendingOrders' => Order::with(['table', 'items.product'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->get(),
            'preparingOrders' => Order::with(['table', 'items.product'])
                ->where('status', 'preparing')
                ->orderBy('created_at', 'asc')
                ->get(),
        ];
    }
    
    public function startPreparing($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'pending') {
            $order->update([
                'status' => 'preparing',
                'employee_id' => auth()->id() // Asignar el cocinero actual
            ]);
            
            $this->emit('orderStatusChanged');
            $this->notify('success', '¡Pedido en preparación!');
        }
    }
    
    public function markReady($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'preparing') {
            $order->update(['status' => 'ready']);
            
            $this->emit('orderStatusChanged');
            $this->notify('success', '¡Pedido listo para entregar!');
        }
    }
    
    protected function notify($type, $message)
    {
        session()->flash($type, $message);
    }
}

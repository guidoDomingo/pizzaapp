<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;

class WaiterOrdersWidget extends Widget
{
    protected static string $view = 'filament.widgets.waiter-orders-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 3;
    
    protected static ?string $heading = '🍽️ Tablero de Meseros';

    public function getViewData(): array
    {
        return [
            'readyOrders' => Order::with(['table', 'items.product'])
                ->where('status', 'ready')
                ->orderBy('updated_at', 'asc')
                ->get(),
            'deliveringOrders' => Order::with(['table', 'items.product'])
                ->where('status', 'delivering')
                ->orderBy('updated_at', 'asc')
                ->get(),
        ];
    }
    
    public function startDelivering($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'ready') {
            $order->update([
                'status' => 'delivering',
                'employee_id' => auth()->id() // Asignar el mesero actual
            ]);
            
            $this->emit('orderStatusChanged');
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
            
            $this->emit('orderStatusChanged');
            $this->notify('success', '¡Pedido entregado! Mesa liberada.');
        }
    }
    
    protected function notify($type, $message)
    {
        session()->flash($type, $message);
    }
}

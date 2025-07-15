<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;
use App\Models\Table as RestaurantTable;
use App\Models\Product;
use App\Models\Category;

class OrderManagementWidget extends Widget
{
    protected static string $view = 'filament.widgets.order-management-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 1;
    
    protected static ?string $heading = '🛒 Centro de Pedidos';

    public function getViewData(): array
    {
        return [
            'orders' => Order::with(['table', 'items.product'])
                ->whereIn('status', ['pending', 'preparing'])
                ->orderBy('created_at', 'desc')
                ->get(),
            'tables' => RestaurantTable::with('currentOrder')->get(),
            'categories' => Category::with('products')->where('is_active', true)->get(),
        ];
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $navigationLabel = 'Centro de Operaciones';
    protected static ?string $title = '🎯 Centro de Operaciones';
    protected static ?string $navigationGroup = null;
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = null; // Usar el slug por defecto

    public $activeTab = 'overview';

    public function mount(): void
    {
        // Initialize the active tab
        $this->activeTab = 'overview';
    }

    // ============ OVERVIEW STATS ============
    public function getOverviewStats()
    {
        $today = Carbon::today();
        
        return [
            'pending' => Order::where('status', 'pending')->whereNotNull('paid_at')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'delivering' => Order::where('status', 'delivering')->count(),
            'orders_today' => Order::whereDate('created_at', $today)->count(),
            'daily_sales' => Order::whereDate('created_at', $today)
                ->whereNotNull('paid_at')->sum('total'),
            'revenue_today' => Order::whereDate('created_at', $today)
                ->whereNotNull('paid_at')->sum('total'),
            'active_orders' => Order::whereIn('status', ['pending', 'preparing', 'ready', 'delivering'])->count(),
            'available_tables' => Table::where('status', 'available')->count(),
            'total_tables' => Table::count(),
            'pending_payments' => Order::where('status', 'pending')->whereNull('paid_at')->count(),
        ];
    }

    // ============ KITCHEN OPERATIONS ============
    public function getPendingOrders()
    {
        return Order::with(['table', 'items.product'])
            ->where('status', 'pending')
            ->whereNotNull('paid_at')
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
            
            $this->notify('success', '¡Preparación iniciada!');
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

    // ============ WAITER OPERATIONS ============
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
            
            if ($order->table) {
                $order->table->update(['status' => 'available']);
            }
            
            $this->notify('success', '¡Pedido entregado! Mesa liberada.');
        }
    }

    // ============ ANALYTICS ============
    public function getAnalyticsData()
    {
        $today = Carbon::today();
        $week = Carbon::now()->subDays(7);
        
        return [
            'daily_orders' => Order::whereDate('created_at', $today)->count(),
            'weekly_orders' => Order::where('created_at', '>=', $week)->count(),
            'most_popular_products' => Product::withCount(['orderItems' => function($query) use ($today) {
                $query->whereHas('order', function($q) use ($today) {
                    $q->whereDate('created_at', $today);
                });
            }])->orderBy('order_items_count', 'desc')->take(5)->get(),
            'busiest_hours' => Order::whereDate('created_at', $today)
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
        ];
    }

    // ============ TAB MANAGEMENT ============
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // ============ REFRESH ============
    public function refreshData()
    {
        // Método para refresh automático
        $this->render();
    }

    protected $listeners = [
        'refreshData' => 'refreshData',
        'orderStatusChanged' => 'refreshData'
    ];

    // ============ COMPUTED PROPERTIES ============
    public function getStatsProperty()
    {
        return $this->getOverviewStats();
    }

    public function getAnalyticsProperty()
    {
        return $this->getAnalyticsData();
    }

    // ============ VIEW DATA ============
    protected function getViewData(): array
    {
        return [
            'stats' => $this->getOverviewStats(),
            'analytics' => $this->getAnalyticsData(),
        ];
    }

    // Override the render method to fix potential slot issues
    public function render(): View
    {
        $viewData = $this->getViewData();
        return view(static::$view, array_merge($viewData, [
            'activeTab' => $this->activeTab,
        ]));
    }
}

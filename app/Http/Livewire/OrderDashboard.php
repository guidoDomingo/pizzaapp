<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Table as RestaurantTable;
use App\Models\Product;

class OrderDashboard extends Component
{
    public $selectedTable = null;
    public $newOrder = [
        'table_id' => null,
        'items' => []
    ];
    
    // Property to help Livewire track changes
    public $refreshKey = 0;

    protected $listeners = ['refresh' => '$refresh'];

    public function hydrate()
    {
        // Ensure proper hydration
    }

    public function mount()
    {
        // No cargar datos en mount
    }

    public function selectTable($tableId)
    {
        $this->selectedTable = $tableId;
        $this->newOrder['table_id'] = $tableId;
        $this->refreshKey++;
    }

    public function addProductToOrder($productId)
    {
        $product = Product::find($productId);
        
        if (isset($this->newOrder['items'][$productId])) {
            $this->newOrder['items'][$productId]['quantity']++;
        } else {
            $this->newOrder['items'][$productId] = [
                'product_id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }
        $this->refreshKey++;
    }

    public function removeProductFromOrder($productId)
    {
        if (isset($this->newOrder['items'][$productId])) {
            $this->newOrder['items'][$productId]['quantity']--;
            
            if ($this->newOrder['items'][$productId]['quantity'] <= 0) {
                unset($this->newOrder['items'][$productId]);
            }
        }
        $this->refreshKey++;
    }

    public function createOrder()
    {
        if (empty($this->newOrder['items']) || !$this->newOrder['table_id']) {
            session()->flash('error', 'Seleccione una mesa y añada productos al pedido.');
            return;
        }

        $subtotal = collect($this->newOrder['items'])->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $tax = $subtotal * 0.18; // 18% de impuestos
        $total = $subtotal + $tax;

        $order = Order::create([
            'restaurant_id' => 1,
            'table_id' => $this->newOrder['table_id'],
            'employee_id' => 1, // Por ahora usar empleado fijo
            'status' => 'pending',
            'order_type' => 'dine_in',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_status' => 'pending'
        ]);

        foreach ($this->newOrder['items'] as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity'],
                'status' => 'pending'
            ]);
        }

        // Cambiar estado de la mesa
        RestaurantTable::find($this->newOrder['table_id'])->update(['status' => 'occupied']);

        // Limpiar formulario
        $this->newOrder = ['table_id' => null, 'items' => []];
        $this->selectedTable = null;
        $this->refreshKey++;

        session()->flash('success', 'Pedido creado exitosamente.');
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        $order->update(['status' => $status]);

        if ($status === 'delivered') {
            // Liberar mesa cuando se entrega el pedido
            if ($order->table) {
                $order->table->update(['status' => 'available']);
            }
        }
        
        $this->refreshKey++;
    }

    public function getOrderTotal()
    {
        return collect($this->newOrder['items'])->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function render()
    {
        // Cargar datos directamente en render
        $orders = Order::with(['table', 'items.product', 'customer'])
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->get();

        $tables = RestaurantTable::where('restaurant_id', 1)->get();
        
        $products = Product::where('restaurant_id', 1)
            ->where('is_available', true)
            ->with('category')
            ->get();
        
        // Agrupar productos por categoría de forma simple
        $groupedProducts = [];
        foreach ($products as $product) {
            $categoryName = $product->category ? $product->category->name : 'Sin Categoría';
            if (!isset($groupedProducts[$categoryName])) {
                $groupedProducts[$categoryName] = [];
            }
            $groupedProducts[$categoryName][] = $product;
        }
        
        return view('livewire.order-dashboard', [
            'orders' => $orders,
            'tables' => $tables,
            'products' => $groupedProducts
        ]);
    }
}

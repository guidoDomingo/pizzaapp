<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Table as RestaurantTable;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;

class OrderCart extends Component
{
    public $selectedTable = null;
    public $cart = [];
    public $tables = [];
    public $categories = [];
    public $showCart = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->tables = RestaurantTable::where('status', 'available')->get();
        $this->categories = Category::with('products')->where('is_active', true)->get();
    }

    public function selectTable($tableId)
    {
        $this->selectedTable = $tableId;
        $this->emit('tableSelected', $tableId);
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'product_id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }
        
        $this->showCart = true;
        $this->emit('cartUpdated');
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->emit('cartUpdated');
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($productId);
        } else {
            $this->cart[$productId]['quantity'] = $quantity;
        }
        $this->emit('cartUpdated');
    }

    public function createOrder()
    {
        if (!$this->selectedTable || empty($this->cart)) {
            return;
        }

        $table = RestaurantTable::find($this->selectedTable);
        
        $order = Order::create([
            'restaurant_id' => $table->restaurant_id,
            'table_id' => $this->selectedTable,
            'order_number' => 'ORD-' . time(),
            'status' => 'pending',
            'subtotal' => $this->getSubtotal(),
            'tax' => $this->getTax(),
            'total' => $this->getTotal(),
        ]);

        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
        }

        // Actualizar estado de la mesa
        $table->update(['status' => 'occupied']);

        // Limpiar carrito
        $this->cart = [];
        $this->selectedTable = null;
        $this->showCart = false;
        
        $this->emit('orderCreated', $order->id);
        session()->flash('success', 'Pedido creado exitosamente: ' . $order->order_number);
        
        $this->loadData();
    }

    public function getSubtotal()
    {
        return collect($this->cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function getTax()
    {
        return $this->getSubtotal() * 0.18; // 18% de impuesto
    }

    public function getTotal()
    {
        return $this->getSubtotal() + $this->getTax();
    }

    public function render()
    {
        return view('livewire.order-cart');
    }
}

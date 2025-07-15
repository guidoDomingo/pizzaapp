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
            session()->flash('error', 'Selecciona una mesa y agrega productos al carrito.');
            return;
        }

        try {
            $table = RestaurantTable::find($this->selectedTable);
            
            // Get restaurant_id from table or use the first restaurant
            $restaurantId = $table->restaurant_id ?? \App\Models\Restaurant::first()->id;
            
            $order = Order::create([
                'restaurant_id' => $restaurantId,
                'table_id' => $this->selectedTable,
                'order_number' => 'ORD-' . time(),
                'status' => 'pending', // Temporal: usar pending hasta migrar 'cart'
                'order_type' => 'dine_in',
                'subtotal' => $this->getSubtotal(),
                'tax' => $this->getTax(),
                'total' => $this->getTotal(),
            ]);

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity']
                ]);
            }

            // Abrir modal de pago en lugar de crear directamente el pedido
            $this->emit('openPaymentModal', $order->id);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el pedido: ' . $e->getMessage());
        }
    }

    public function paymentCompleted($orderId)
    {
        // Actualizar estado de la mesa después del pago exitoso
        if ($this->selectedTable) {
            $table = RestaurantTable::find($this->selectedTable);
            $table->update(['status' => 'occupied']);
        }

        // Limpiar carrito
        $this->cart = [];
        $this->selectedTable = null;
        $this->showCart = false;
        
        session()->flash('success', 'Pedido y pago procesados exitosamente. La orden ha sido enviada a cocina.');
        
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

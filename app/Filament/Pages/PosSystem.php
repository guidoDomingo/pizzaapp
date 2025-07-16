<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Table;
use App\Models\Customer;
use App\Models\OrderItem;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Livewire\Component;

class PosSystem extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationGroup = 'Operaciones';
    
    protected static ?int $navigationSort = 0;
    
    protected static string $view = 'filament.pages.pos-system';
    
    protected static ?string $title = 'Sistema POS';
    
    protected static ?string $navigationLabel = 'Sistema POS';

    public $selectedCategory = null;
    public $cart = [];
    public $selectedTable = null;
    public $customerName = '';
    public $customerPhone = '';
    public $notes = '';
    public $activeTab = 'products'; // products, cart, payment

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $firstCategory = Category::first();
        $this->selectedCategory = $firstCategory?->id;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) {
            return;
        }

        $existingItem = collect($this->cart)->where('product_id', $productId)->first();
        
        if ($existingItem) {
            // Si ya existe, incrementar cantidad
            $this->cart = collect($this->cart)->map(function ($item) use ($productId) {
                if ($item['product_id'] == $productId) {
                    $item['quantity']++;
                    $item['subtotal'] = $item['quantity'] * $item['price'];
                }
                return $item;
            })->toArray();
        } else {
            // Agregar nuevo item
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
            ];
        }

        $this->emit('cartUpdated');
        
        Notification::make()
            ->title('Producto agregado')
            ->body("{$product->name} agregado al carrito")
            ->success()
            ->send();
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->emit('cartUpdated');
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($index);
            return;
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['subtotal'] = $this->cart[$index]['price'] * $quantity;
        $this->emit('cartUpdated');
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->emit('cartUpdated');
    }

    public function getCartTotal()
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getCartCount()
    {
        return collect($this->cart)->sum('quantity');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function processOrder()
    {
        if (empty($this->cart)) {
            Notification::make()
                ->title('Error')
                ->body('El carrito está vacío')
                ->danger()
                ->send();
            return;
        }

        // Crear o encontrar cliente
        $customer = null;
        if (!empty($this->customerName)) {
            $customer = Customer::firstOrCreate([
                'name' => $this->customerName,
            ], [
                'phone' => $this->customerPhone,
            ]);
        }

        // Crear orden
        $order = Order::create([
            'order_number' => 'ORD-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT),
            'customer_id' => $customer?->id,
            'table_id' => $this->selectedTable,
            'status' => 'pending',
            'total' => $this->getCartTotal(),
            'notes' => $this->notes,
            'restaurant_id' => 1, // Assuming first restaurant
        ]);

        // Crear items de la orden
        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'price' => $item['price'] * $item['quantity'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

        // Ocupar mesa si se seleccionó una
        if ($this->selectedTable) {
            Table::find($this->selectedTable)->update(['status' => 'occupied']);
        }

        Notification::make()
            ->title('¡Orden creada exitosamente!')
            ->body("Orden #{$order->order_number} por $" . number_format($this->getCartTotal(), 2))
            ->success()
            ->duration(5000)
            ->send();

        // Limpiar formulario
        $this->reset(['cart', 'selectedTable', 'customerName', 'customerPhone', 'notes']);
        $this->activeTab = 'products';
    }

    public function getCategories()
    {
        return Category::with('products')->get();
    }

    public function getProductsByCategory()
    {
        if (!$this->selectedCategory) {
            return collect();
        }
        
        return Product::where('category_id', $this->selectedCategory)->get();
    }

    public function getAvailableTables()
    {
        return Table::where('status', 'available')->get();
    }
}

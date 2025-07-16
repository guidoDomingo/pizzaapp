<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use App\Models\Customer;
use App\Models\OrderItem;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class CreateOrder extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    
    protected static ?string $navigationGroup = 'Operaciones';
    
    protected static ?int $navigationSort = 4;
    
    protected static string $view = 'filament.pages.create-order';
    
    protected static ?string $title = 'Nuevo Pedido';
    
    protected static ?string $navigationLabel = 'Nuevo Pedido';

    public $table_id;
    public $customer_name;
    public $customer_phone;
    public $items = [];
    public $notes;
    
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('table_id')
                        ->label('Mesa')
                        ->options(Table::where('status', 'available')->pluck('number', 'id'))
                        ->nullable()
                        ->placeholder('Seleccionar mesa (opcional)'),
                        
                    Forms\Components\TextInput::make('customer_name')
                        ->label('Nombre del Cliente')
                        ->placeholder('Nombre del cliente'),
                ]),
                
            Forms\Components\TextInput::make('customer_phone')
                ->label('Teléfono del Cliente')
                ->placeholder('Teléfono (opcional)')
                ->tel(),
                
            Forms\Components\Repeater::make('items')
                ->label('Productos del Pedido')
                ->schema([
                    Forms\Components\Select::make('product_id')
                        ->label('Producto')
                        ->options(Product::all()->pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $product = Product::find($state);
                                $set('price', $product->price);
                                $set('subtotal', $product->price);
                            }
                        }),
                        
                    Forms\Components\TextInput::make('quantity')
                        ->label('Cantidad')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $price = $get('price') ?? 0;
                            $set('subtotal', $price * $state);
                        }),
                        
                    Forms\Components\TextInput::make('price')
                        ->label('Precio Unit.')
                        ->numeric()
                        ->disabled()
                        ->prefix('$'),
                        
                    Forms\Components\TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->numeric()
                        ->disabled()
                        ->prefix('$'),
                ])
                ->columns(4)
                ->defaultItems(1)
                ->createItemButtonLabel('Agregar Producto')
                ->collapsible(),
                
            Forms\Components\Textarea::make('notes')
                ->label('Notas Especiales')
                ->placeholder('Instrucciones especiales para la cocina...')
                ->rows(3),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        
        if (empty($data['items'])) {
            Notification::make()
                ->title('Error')
                ->body('Debe agregar al menos un producto al pedido.')
                ->danger()
                ->send();
            return;
        }

        // Crear o encontrar cliente
        $customer = null;
        if (!empty($data['customer_name'])) {
            $customer = Customer::firstOrCreate([
                'name' => $data['customer_name'],
            ], [
                'phone' => $data['customer_phone'],
            ]);
        }

        // Calcular total
        $total = collect($data['items'])->sum('subtotal');

        // Crear orden
        $order = Order::create([
            'order_number' => 'ORD-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT),
            'customer_id' => $customer?->id,
            'table_id' => $data['table_id'],
            'status' => 'pending',
            'total' => $total,
            'notes' => $data['notes'],
        ]);

        // Crear items de la orden
        foreach ($data['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Ocupar mesa si se seleccionó una
        if ($data['table_id']) {
            Table::find($data['table_id'])->update(['status' => 'occupied']);
        }

        Notification::make()
            ->title('¡Pedido creado exitosamente!')
            ->body("Orden #{$order->order_number} creada. Total: $" . number_format($total, 2))
            ->success()
            ->send();

        // Limpiar formulario
        $this->form->fill([
            'table_id' => null,
            'customer_name' => '',
            'customer_phone' => '',
            'items' => [['product_id' => null, 'quantity' => 1]],
            'notes' => '',
        ]);
    }

    public function getTotal(): float
    {
        return collect($this->items)->sum('subtotal') ?? 0;
    }
}

<div class="space-y-6">
    <!-- Mensajes de Flash -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-filament::card>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $tables->where('status', 'available')->count() }}</div>
                <div class="text-sm text-gray-600">Mesas Disponibles</div>
            </div>
        </x-filament::card>
        
        <x-filament::card>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ count($cart) }}</div>
                <div class="text-sm text-gray-600">Productos en Carrito</div>
            </div>
        </x-filament::card>
        
        <x-filament::card>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">${{ number_format($this->getSubtotal(), 2) }}</div>
                <div class="text-sm text-gray-600">Subtotal</div>
            </div>
        </x-filament::card>
        
        <x-filament::card>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">${{ number_format($this->getTotal(), 2) }}</div>
                <div class="text-sm text-gray-600">Total</div>
            </div>
        </x-filament::card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Selección de Mesas -->
        <div class="lg:col-span-3">
            <x-filament::card>
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold flex items-center">
                        🪑 Seleccionar Mesa
                    </h3>
                    
                    @if($selectedTable)
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="text-sm font-medium text-blue-800 text-center">
                                ✅ {{ $tables->find($selectedTable)->number ?? 'N/A' }}
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="text-sm text-yellow-800 text-center">
                                ⚠️ Selecciona una mesa
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($tables as $table)
                            <button 
                                wire:click="selectTable({{ $table->id }})"
                                class="p-3 border-2 rounded-lg text-center transition-all duration-200 {{ 
                                    $selectedTable == $table->id ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-300 hover:border-blue-300' 
                                }}">
                                <div class="font-medium text-sm">{{ $table->number }}</div>
                                <div class="text-xs text-gray-600">{{ $table->capacity }}p</div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </x-filament::card>
        </div>

        <!-- Menú de Productos -->
        <div class="lg:col-span-6">
            <x-filament::card>
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold flex items-center">
                        🍕 Menú del Restaurante
                    </h3>
                    
                    @foreach($categories as $category)
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-700 border-b pb-2 flex items-center">
                                <span class="mr-2">
                                    @if($category->name === 'Pizzas') 🍕
                                    @elseif($category->name === 'Bebidas') 🥤
                                    @elseif($category->name === 'Postres') 🍰
                                    @else 🍽️
                                    @endif
                                </span>
                                {{ $category->name }}
                            </h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($category->products as $product)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow bg-gray-50">
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-start">
                                                <h5 class="font-medium text-sm">{{ $product->name }}</h5>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-xs text-gray-600 leading-relaxed">
                                                {{ $product->description }}
                                            </p>
                                            
                                            <button
                                                wire:click="addToCart({{ $product->id }})"
                                                class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors"
                                            >
                                                ➕ Agregar al Carrito
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::card>
        </div>

        <!-- Carrito de Compras -->
        <div class="lg:col-span-3">
            <div class="sticky top-6">
                <x-filament::card>
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold flex items-center">
                            🛒 Carrito de Compras
                        </h3>

                        @if(!empty($cart))
                            <div class="space-y-3 max-h-80 overflow-y-auto">
                                @foreach($cart as $item)
                                    <div class="bg-white border rounded-lg p-3">
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium">{{ $item['name'] }}</div>
                                                    <div class="text-xs text-gray-600">
                                                        ${{ number_format($item['price'], 2) }} c/u
                                                    </div>
                                                </div>
                                                <button
                                                    wire:click="removeFromCart({{ $item['product_id'] }})"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-medium transition-colors"
                                                >
                                                    ×
                                                </button>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <button
                                                        wire:click="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] - 1 }})"
                                                        class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs font-medium transition-colors"
                                                    >
                                                        -
                                                    </button>
                                                    
                                                    <span class="text-sm font-medium w-8 text-center">
                                                        {{ $item['quantity'] }}
                                                    </span>
                                                    
                                                    <button
                                                        wire:click="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] + 1 }})"
                                                        class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs font-medium transition-colors"
                                                    >
                                                        +
                                                    </button>
                                                </div>
                                                
                                                <div class="text-sm font-bold text-green-600">
                                                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Resumen de totales -->
                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($this->getSubtotal(), 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Impuesto (18%):</span>
                                    <span>${{ number_format($this->getTax(), 2) }}</span>
                                </div>
                                <div class="flex justify-between font-bold text-lg border-t pt-2">
                                    <span>Total:</span>
                                    <span class="text-green-600">${{ number_format($this->getTotal(), 2) }}</span>
                                </div>
                            </div>

                            <!-- Botón de crear orden -->
                            <button
                                wire:click="createOrder"
                                {{ !$selectedTable ? 'disabled' : '' }}
                                class="w-full {{ $selectedTable ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed' }} text-white px-6 py-3 rounded text-lg font-medium transition-colors"
                            >
                                @if($selectedTable)
                                    🍕 Proceder al Pago
                                @else
                                    ⚠️ Selecciona Mesa
                                @endif
                            </button>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl text-gray-300 mb-4">🛒</div>
                                <h4 class="text-lg font-medium text-gray-600 mb-2">Carrito Vacío</h4>
                                <p class="text-sm text-gray-500 mb-4">
                                    Agrega productos del menú para comenzar
                                </p>
                                <button
                                    disabled
                                    class="bg-gray-400 cursor-not-allowed text-white px-4 py-2 rounded text-sm font-medium"
                                >
                                    Carrito Vacío
                                </button>
                            </div>
                        @endif
                    </div>
                </x-filament::card>
            </div>
        </div>
    </div>

    <!-- Modal de Pago -->
    @livewire('payment-modal')
    
    <!-- Visualizador de Tickets -->
    @livewire('ticket-viewer')
</div>

<script>
    document.addEventListener('livewire:load', function () {
        // Escuchar evento para abrir el modal de pago
        Livewire.on('openPaymentModal', orderId => {
            Livewire.emit('openPayment', orderId);
        });

        // Escuchar evento de pago completado
        Livewire.on('paymentCompleted', orderId => {
            @this.paymentCompleted(orderId);
        });

        // Escuchar evento para mostrar ticket
        Livewire.on('showTicket', ticketData => {
            // El ticket se mostrará automáticamente
            console.log('Ticket generado:', ticketData.ticket_number);
        });

        // Escuchar evento de ticket impreso
        Livewire.on('ticketPrinted', ticketNumber => {
            console.log('Ticket impreso:', ticketNumber);
        });
    });
</script>

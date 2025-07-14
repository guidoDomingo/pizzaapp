<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 p-6">
    <!-- Selección de Mesas -->
    <div class="lg:col-span-1">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">🪑 Mesas Disponibles</h3>
        <div class="grid grid-cols-2 gap-2">
            @foreach($tables as $table)
                <button 
                    wire:click="selectTable({{ $table->id }})"
                    class="p-3 border-2 rounded-lg text-center transition-all duration-200 {{ 
                        $selectedTable == $table->id ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-300 hover:border-blue-300' 
                    }}">
                    <div class="font-medium text-sm">{{ $table->number }}</div>
                    <div class="text-xs text-gray-600">{{ $table->capacity }} personas</div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Productos por Categoría -->
    <div class="lg:col-span-2">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">🍕 Menú</h3>
        
        @foreach($categories as $category)
            <div class="mb-6">
                <h4 class="font-medium text-gray-700 mb-3 border-b pb-1">{{ $category->name }}</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($category->products as $product)
                        <div class="border rounded-lg p-3 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-2">
                                <h5 class="font-medium text-sm">{{ $product->name }}</h5>
                                <span class="text-green-600 font-bold text-sm">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mb-3">{{ $product->description }}</p>
                            <button 
                                wire:click="addToCart({{ $product->id }})"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded text-sm font-medium transition-colors">
                                ➕ Agregar
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Carrito de Compras -->
    <div class="lg:col-span-1">
        <div class="sticky top-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">🛒 Carrito</h3>
            
            @if($selectedTable)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <div class="text-sm font-medium text-blue-800">
                        Mesa Seleccionada: {{ $tables->find($selectedTable)->number ?? 'N/A' }}
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <div class="text-sm text-yellow-800">
                        ⚠️ Selecciona una mesa primero
                    </div>
                </div>
            @endif

            @if(!empty($cart))
                <div class="bg-white border rounded-lg p-4 mb-4">
                    <div class="space-y-3">
                        @foreach($cart as $item)
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <div class="text-sm font-medium">{{ $item['name'] }}</div>
                                    <div class="text-xs text-gray-600">${{ number_format($item['price'], 2) }} c/u</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button 
                                        wire:click="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] - 1 }})"
                                        class="w-6 h-6 bg-gray-200 rounded-full text-xs hover:bg-gray-300">-</button>
                                    <span class="text-sm font-medium w-8 text-center">{{ $item['quantity'] }}</span>
                                    <button 
                                        wire:click="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] + 1 }})"
                                        class="w-6 h-6 bg-gray-200 rounded-full text-xs hover:bg-gray-300">+</button>
                                    <button 
                                        wire:click="removeFromCart({{ $item['product_id'] }})"
                                        class="w-6 h-6 bg-red-200 text-red-600 rounded-full text-xs hover:bg-red-300">×</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t pt-3 mt-3 space-y-1">
                        <div class="flex justify-between text-sm">
                            <span>Subtotal:</span>
                            <span>${{ number_format($this->getSubtotal(), 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Impuesto (18%):</span>
                            <span>${{ number_format($this->getTax(), 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Total:</span>
                            <span class="text-green-600">${{ number_format($this->getTotal(), 2) }}</span>
                        </div>
                    </div>
                </div>

                <button 
                    wire:click="createOrder"
                    @if(!$selectedTable) disabled @endif
                    class="w-full py-3 rounded-lg font-medium transition-all duration-200 {{ 
                        $selectedTable ? 'bg-green-500 hover:bg-green-600 text-white shadow-lg hover:shadow-xl' : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                    }}">
                    {{ $selectedTable ? '🍕 Crear Pedido' : '⚠️ Selecciona Mesa' }}
                </button>
            @else
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2">🛒</div>
                    <p class="text-sm">Carrito vacío</p>
                    <p class="text-xs">Agrega productos del menú</p>
                </div>
            @endif
        </div>
    </div>
</div>

<x-filament::page>
    <div class="h-screen flex bg-gray-50" x-data="{ activeTab: @entangle('activeTab') }">
        <!-- Panel izquierdo - Productos -->
        <div class="flex-1 flex flex-col">
            <!-- Pestañas superiores -->
            <div class="bg-white shadow-sm border-b">
                <div class="flex space-x-1 p-4">
                    <button 
                        @click="$wire.setActiveTab('products')"
                        class="px-4 py-2 rounded-lg font-medium transition-colors flex items-center"
                        :class="activeTab === 'products' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Productos
                    </button>
                    <button 
                        @click="$wire.setActiveTab('cart')"
                        class="px-4 py-2 rounded-lg font-medium transition-colors flex items-center"
                        :class="activeTab === 'cart' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m2.6 8L6 21H4M7 13v6a2 2 0 002 2h8a2 2 0 002-2v-6M7 13l-1-8H4m3 8l1 8h8l1-8M9 17h6"></path>
                        </svg>
                        Carrito 
                        @if($this->getCartCount() > 0)
                            <span class="ml-2 bg-red-500 text-white rounded-full px-2 py-1 text-xs">{{ $this->getCartCount() }}</span>
                        @endif
                    </button>
                </div>
            </div>

            <!-- Contenido de productos -->
            <div x-show="activeTab === 'products'" class="flex-1 flex">
                <!-- Categorías -->
                <div class="w-64 bg-white shadow-sm border-r">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Categorías</h3>
                    </div>
                    <div class="p-2 space-y-1">
                        @foreach($this->getCategories() as $category)
                        <button 
                            wire:click="selectCategory({{ $category->id }})"
                            class="w-full text-left px-3 py-3 rounded-lg font-medium transition-colors flex items-center"
                            class="{{ $selectedCategory == $category->id ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'text-gray-700 hover:bg-gray-100' }}"
                        >
                            <span class="text-2xl mr-3">
                                @if($category->name === 'Pizzas') 🍕
                                @elseif($category->name === 'Bebidas') 🥤
                                @elseif($category->name === 'Entradas') 🥗
                                @else 🍽️
                                @endif
                            </span>
                            <div>
                                <div class="font-medium">{{ $category->name }}</div>
                                <div class="text-xs text-gray-500">{{ $category->products->count() }} productos</div>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Grid de productos -->
                <div class="flex-1 p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($this->getProductsByCategory() as $product)
                        <div 
                            class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-all cursor-pointer group"
                            wire:click="addToCart({{ $product->id }})"
                        >
                            <div class="p-4">
                                <div class="text-3xl mb-2 text-center">
                                    @if(str_contains(strtolower($product->name), 'pizza')) 🍕
                                    @elseif(str_contains(strtolower($product->name), 'coca') || str_contains(strtolower($product->name), 'sprite')) 🥤
                                    @elseif(str_contains(strtolower($product->name), 'agua')) 💧
                                    @elseif(str_contains(strtolower($product->name), 'jugo')) 🧃
                                    @elseif(str_contains(strtolower($product->name), 'cerveza')) 🍺
                                    @elseif(str_contains(strtolower($product->name), 'ensalada')) 🥗
                                    @elseif(str_contains(strtolower($product->name), 'pan')) 🍞
                                    @elseif(str_contains(strtolower($product->name), 'alitas')) 🍗
                                    @elseif(str_contains(strtolower($product->name), 'nachos')) 🌮
                                    @else 🍽️
                                    @endif
                                </div>
                                <h4 class="font-semibold text-gray-900 text-center mb-2 group-hover:text-blue-600">
                                    {{ $product->name }}
                                </h4>
                                <p class="text-xs text-gray-600 text-center mb-3 line-clamp-2">
                                    {{ $product->description }}
                                </p>
                                <div class="text-center">
                                    <span class="text-xl font-bold text-green-600">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                </div>
                                <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="bg-blue-100 text-blue-700 text-center py-2 rounded-lg text-sm font-medium">
                                        + Agregar al carrito
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contenido del carrito -->
            <div x-show="activeTab === 'cart'" class="flex-1 p-6">
                @if(empty($cart))
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <div class="text-6xl mb-4">🛒</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Carrito vacío</h3>
                        <p class="text-gray-600">Agrega productos desde la pestaña de productos</p>
                        <button 
                            @click="$wire.setActiveTab('products')"
                            class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center mx-auto"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ver Productos
                        </button>
                    </div>
                </div>
                @else
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold">Productos en el carrito</h3>
                        <button 
                            wire:click="clearCart"
                            class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Limpiar carrito
                        </button>
                    </div>
                    
                    @foreach($cart as $index => $item)
                    <div class="bg-white rounded-lg shadow-sm border p-4 flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium">{{ $item['name'] }}</h4>
                            <p class="text-sm text-gray-600">${{ number_format($item['price'], 2) }} c/u</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center border rounded-lg bg-white">
                                <button 
                                    wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                    class="px-3 py-1 hover:bg-gray-100 text-red-600 font-semibold"
                                >
                                    −
                                </button>
                                <span class="px-3 py-1 border-x min-w-[3rem] text-center font-medium">{{ $item['quantity'] }}</span>
                                <button 
                                    wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                    class="px-3 py-1 hover:bg-gray-100 text-green-600 font-semibold"
                                >
                                    +
                                </button>
                            </div>
                            <span class="font-semibold w-20 text-right text-green-600">${{ number_format($item['subtotal'], 2) }}</span>
                            <button 
                                wire:click="removeFromCart({{ $index }})"
                                class="text-red-600 hover:text-red-700 p-1 hover:bg-red-50 rounded"
                                title="Eliminar producto"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Panel derecho - Resumen y checkout -->
        <div class="w-96 bg-white shadow-xl border-l flex flex-col">
            <!-- Header -->
            <div class="p-6 border-b bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-900">Resumen del Pedido</h2>
            </div>

            <!-- Información del pedido -->
            <div class="flex-1 p-6 space-y-6">
                <!-- Selección de mesa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mesa (opcional)</label>
                    <select wire:model="selectedTable" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Para llevar</option>
                        @foreach($this->getAvailableTables() as $table)
                        <option value="{{ $table->id }}">{{ $table->number }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Información del cliente -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del cliente</label>
                        <input 
                            wire:model="customerName" 
                            type="text" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Nombre del cliente"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input 
                            wire:model="customerPhone" 
                            type="tel" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Teléfono (opcional)"
                        >
                    </div>
                </div>

                <!-- Notas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notas especiales</label>
                    <textarea 
                        wire:model="notes" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 h-20"
                        placeholder="Instrucciones especiales..."
                    ></textarea>
                </div>

                <!-- Resumen del carrito -->
                @if(!empty($cart))
                <div class="border-t pt-4">
                    <h4 class="font-medium mb-3">Items ({{ $this->getCartCount() }})</h4>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($cart as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                            <span>${{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer con total y botón -->
            <div class="p-6 border-t bg-gray-50">
                @if(!empty($cart))
                <div class="mb-4">
                    <div class="flex justify-between items-center text-xl font-bold">
                        <span>Total:</span>
                        <span class="text-green-600">${{ number_format($this->getCartTotal(), 2) }}</span>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="space-y-3">
                    <button 
                        wire:click="processOrder"
                        class="w-full bg-green-600 text-white py-4 px-6 rounded-lg font-bold text-lg hover:bg-green-700 transition-colors border-2 border-green-500 shadow-lg"
                        style="min-height: 60px;"
                    >
                        ✅ PROCESAR PEDIDO
                    </button>
                    
                    <button 
                        wire:click="clearCart"
                        class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-bold hover:bg-red-700 transition-colors border-2 border-red-500 shadow-md"
                        style="min-height: 50px;"
                    >
                        ❌ CANCELAR PEDIDO
                    </button>
                </div>
                @else
                <div class="text-center text-gray-500 py-8">
                    <div class="text-4xl mb-3">🛒</div>
                    <p class="font-medium">Agrega productos al carrito para continuar</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-filament::page>

<x-layouts.dashboard-clean>
    <div wire:id="order-dashboard-{{ $refreshKey }}" class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Notificaciones -->
        @if (session()->has('success'))
            <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-6 py-4 mx-6 mt-6 rounded-r-lg shadow-md">
                <div class="flex items-center">
                    <span class="text-xl mr-2">✅</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border-l-4 border-red-400 text-red-800 px-6 py-4 mx-6 mt-6 rounded-r-lg shadow-md">
                <div class="flex items-center">
                    <span class="text-xl mr-2">❌</span>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
            <!-- Panel de Órdenes Activas -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Órdenes Activas</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($orders as $order)
                        <div wire:key="order-{{ $order->id }}" class="border rounded-lg p-4 {{ 
                            $order->status === 'pending' ? 'border-yellow-300 bg-yellow-50' : 
                            ($order->status === 'preparing' ? 'border-blue-300 bg-blue-50' : 'border-green-300 bg-green-50') 
                        }}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-600">{{ $order->table->number ?? 'Sin mesa' }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                                </div>
                                <span class="px-2 py-1 rounded text-xs font-medium {{ 
                                    $order->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 
                                    ($order->status === 'preparing' ? 'bg-blue-200 text-blue-800' : 'bg-green-200 text-green-800') 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                            <!-- Items del pedido -->
                            <div class="mb-3">
                                @foreach($order->items as $item)
                                    <div wire:key="item-{{ $item->id }}" class="flex justify-between text-sm">
                                        <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                        <span>${{ number_format($item->total_price, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center pt-3 border-t">
                                <span class="font-semibold">Total: ${{ number_format($order->total, 2) }}</span>
                                <div class="flex space-x-2">
                                    @if($order->status === 'pending')
                                        <button 
                                            wire:click="updateOrderStatus({{ $order->id }}, 'preparing')"
                                            class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                                            Preparar
                                        </button>
                                    @elseif($order->status === 'preparing')
                                        <button 
                                            wire:click="updateOrderStatus({{ $order->id }}, 'ready')"
                                            class="px-3 py-1 bg-green-500 text-white rounded text-xs hover:bg-green-600">
                                            Listo
                                        </button>
                                    @elseif($order->status === 'ready')
                                        <button 
                                            wire:click="updateOrderStatus({{ $order->id }}, 'delivered')"
                                            class="px-3 py-1 bg-purple-500 text-white rounded text-xs hover:bg-purple-600">
                                            Entregar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            No hay órdenes activas
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Panel de Nueva Orden -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Nueva Orden</h2>
                
                <!-- Selección de Mesa -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3 text-gray-800">🪑 Seleccionar Mesa</h3>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($tables as $table)
                            <button 
                                wire:key="table-{{ $table->id }}"
                                wire:click="selectTable({{ $table->id }})"
                                class="p-3 rounded-lg border-2 transition-all duration-200 {{ 
                                    $selectedTable === $table->id ? 'bg-blue-500 text-white border-blue-500 shadow-lg transform scale-105' : 
                                    ($table->status === 'available' ? 'bg-green-50 border-green-300 text-green-800 hover:bg-green-100 hover:shadow-md' : 'bg-red-50 border-red-300 text-red-800 opacity-50 cursor-not-allowed') 
                                }}">
                                <div class="text-sm font-bold">Mesa {{ $table->number }}</div>
                                <div class="text-xs">{{ $table->capacity }} personas</div>
                                <div class="text-xs mt-1">
                                    {{ $table->status === 'available' ? '✅ Libre' : '🔴 Ocupada' }}
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Productos por Categoría -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3 text-gray-800">🍕 Productos</h3>
                    
                    @foreach($products as $categoryName => $categoryProducts)
                        <div wire:key="category-{{ \Str::slug($categoryName) }}" class="mb-6">
                            <h4 class="text-base font-semibold text-gray-800 mb-3 border-b pb-1">{{ $categoryName }}</h4>
                            <div class="space-y-2">
                                @foreach($categoryProducts as $product)
                                    <div wire:key="product-{{ $product->id }}" class="flex justify-between items-center p-3 border rounded-lg hover:shadow-md transition-shadow bg-white">
                                        <div class="flex-1">
                                            <div class="font-semibold text-sm text-gray-800">{{ $product->name }}</div>
                                            <div class="text-sm font-bold text-green-600">${{ number_format($product->price, 2) }}</div>
                                        </div>
                                        <button 
                                            wire:click="addProductToOrder({{ $product->id }})"
                                            class="px-3 py-2 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition-colors shadow-sm hover:shadow-md">
                                            ➕ Agregar
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen del Pedido -->
                @if(!empty($newOrder['items']))
                    <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold mb-3 text-gray-800 flex items-center">
                            🛒 Resumen del Pedido
                            @if($selectedTable)
                                <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded">
                                    Mesa {{ $selectedTable }}
                                </span>
                            @endif
                        </h3>
                        @foreach($newOrder['items'] as $item)
                            <div wire:key="order-item-{{ $item['product_id'] }}" class="flex justify-between items-center mb-2 p-2 bg-white rounded shadow-sm">
                                <span class="text-sm font-medium">{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold text-green-600">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                    <button 
                                        wire:click="removeProductFromOrder({{ $item['product_id'] }})"
                                        class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 transition-colors">
                                        ✕
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <div class="border-t pt-3 mt-3 bg-white p-2 rounded">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span class="text-green-600">${{ number_format(collect($newOrder['items'])->sum(function($item) { return $item['price'] * $item['quantity']; }), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <button 
                        wire:click="createOrder"
                        @if(!$selectedTable) disabled @endif
                        class="w-full py-3 rounded-lg font-medium text-lg transition-all duration-200 {{ 
                            $selectedTable ? 'bg-green-500 text-white hover:bg-green-600 shadow-lg hover:shadow-xl' : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        }}">
                        {{ $selectedTable ? '🍕 Crear Pedido' : '⚠️ Selecciona una Mesa' }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dashboard-clean>

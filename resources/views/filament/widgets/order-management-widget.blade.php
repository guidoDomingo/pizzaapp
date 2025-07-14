<x-filament::widget>
    <x-filament::card>
        <!-- Header con pestañas -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8">
                <button 
                    onclick="showTab('orders')" 
                    id="orders-tab"
                    class="py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600 whitespace-nowrap">
                    📋 Órdenes Activas
                </button>
                <button 
                    onclick="showTab('cart')" 
                    id="cart-tab"
                    class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                    🛒 Crear Pedido
                </button>
            </nav>
        </div>

        <!-- Contenido de pestañas -->
        <div id="orders-content">
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900">📋 Gestión de Pedidos</h2>
                    <div class="flex space-x-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ $orders->count() }} Órdenes Activas
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            {{ $tables->where('status', 'available')->count() }} Mesas Disponibles
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Panel de Órdenes Activas -->
                    <div class="lg:col-span-2 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Órdenes Activas</h3>
                        
                        @if($orders->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($orders as $order)
                                    <div class="border rounded-lg p-4 {{ 
                                        $order->status === 'pending' ? 'border-yellow-300 bg-yellow-50' : 
                                        'border-blue-300 bg-blue-50' 
                                    }}">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-semibold text-lg">{{ $order->order_number }}</h4>
                                                <p class="text-sm text-gray-600">{{ $order->table->number ?? 'Sin mesa' }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                                            </div>
                                            <span class="px-2 py-1 rounded text-xs font-medium {{ 
                                                $order->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-blue-200 text-blue-800' 
                                            }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>

                                        <!-- Items del pedido -->
                                        <div class="space-y-1 mb-3">
                                            @foreach($order->items as $item)
                                                <div class="flex justify-between text-sm">
                                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                                    <span class="font-medium">${{ number_format($item->price * $item->quantity, 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="border-t pt-2 flex justify-between items-center">
                                            <span class="font-bold text-green-600">${{ number_format($order->total, 2) }}</span>
                                            <div class="flex space-x-2">
                                                @if($order->status === 'pending')
                                                    <a href="{{ route('filament.resources.orders.edit', $order) }}" 
                                                       class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                                                        Preparar
                                                    </a>
                                                @endif
                                                @if($order->status === 'preparing')
                                                    <a href="{{ route('filament.resources.orders.edit', $order) }}" 
                                                       class="px-3 py-1 bg-green-500 text-white rounded text-xs hover:bg-green-600">
                                                        Listo
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mt-2">No hay órdenes activas</p>
                            </div>
                        @endif
                    </div>

                    <!-- Panel de Mesas -->
                    <div class="space-y-6">
                        <!-- Mesas -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Estado de Mesas</h3>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($tables as $table)
                                    <div class="border-2 rounded-lg p-3 text-center transition-all duration-200 cursor-pointer {{ 
                                        $table->status === 'available' ? 'border-green-300 bg-green-50 hover:bg-green-100' : 
                                        ($table->status === 'occupied' ? 'border-red-300 bg-red-50' : 'border-yellow-300 bg-yellow-50') 
                                    }}">
                                        <div class="text-sm font-medium">{{ $table->number }}</div>
                                        <div class="text-xs text-gray-600">{{ $table->capacity }} personas</div>
                                        <div class="text-xs mt-1">
                                            @if($table->status === 'available')
                                                <span class="text-green-600">✓ Disponible</span>
                                            @elseif($table->status === 'occupied')
                                                <span class="text-red-600">● Ocupada</span>
                                            @else
                                                <span class="text-yellow-600">⚠ Reservada</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Acciones rápidas -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Acciones Rápidas</h3>
                            <div class="space-y-3">
                                <button 
                                    onclick="showTab('cart')"
                                    class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                                    🛒 Crear Nuevo Pedido
                                </button>
                                
                                <a href="{{ route('filament.resources.orders.index') }}"
                                   class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                                    📋 Ver Todas las Órdenes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido del carrito -->
        <div id="cart-content" style="display: none;">
            @livewire('order-cart')
        </div>

        <script>
            function showTab(tabName) {
                // Ocultar todos los contenidos
                document.getElementById('orders-content').style.display = 'none';
                document.getElementById('cart-content').style.display = 'none';
                
                // Resetear estilos de las pestañas
                document.getElementById('orders-tab').className = 'py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap';
                document.getElementById('cart-tab').className = 'py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap';
                
                // Mostrar el contenido seleccionado
                if (tabName === 'orders') {
                    document.getElementById('orders-content').style.display = 'block';
                    document.getElementById('orders-tab').className = 'py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600 whitespace-nowrap';
                } else if (tabName === 'cart') {
                    document.getElementById('cart-content').style.display = 'block';
                    document.getElementById('cart-tab').className = 'py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600 whitespace-nowrap';
                }
            }
        </script>
    </x-filament::card>
</x-filament::widget>

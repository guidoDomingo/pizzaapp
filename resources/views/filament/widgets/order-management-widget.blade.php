<x-filament::widget>
    <div class="space-y-6">
        <!-- Accesos Rápidos a Tableros -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">👨‍🍳 Tablero de Cocina</h3>
                        <p class="text-sm text-orange-100">Gestionar preparación de pedidos</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $orders->whereIn('status', ['pending', 'preparing'])->count() }}</div>
                        <div class="text-xs text-orange-100">Pedidos en Cocina</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">🍽️ Tablero de Meseros</h3>
                        <p class="text-sm text-green-100">Gestionar entrega de pedidos</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $orders->whereIn('status', ['ready', 'delivering'])->count() }}</div>
                        <div class="text-xs text-green-100">Listos para Entregar</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $orders->count() }}</div>
                    <div class="text-sm text-gray-600">Órdenes Activas</div>
                </div>
            </x-filament::card>
            
            <x-filament::card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $tables->where('status', 'available')->count() }}</div>
                    <div class="text-sm text-gray-600">Mesas Disponibles</div>
                </div>
            </x-filament::card>
            
            <x-filament::card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600">${{ number_format($orders->sum('total'), 2) }}</div>
                    <div class="text-sm text-gray-600">Total Pendiente</div>
                </div>
            </x-filament::card>
        </div>

        <!-- Pestañas principales -->
        <div class="bg-white rounded-lg shadow">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button 
                        onclick="switchTab('orders')" 
                        id="tab-orders"
                        class="tab-button bg-blue-50 border-blue-500 text-blue-600 whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm"
                    >
                        📋 Órdenes Activas
                    </button>
                    <button 
                        onclick="switchTab('create')" 
                        id="tab-create"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm"
                    >
                        🛒 Crear Pedido
                    </button>
                    <button 
                        onclick="switchTab('tables')" 
                        id="tab-tables"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm"
                    >
                        🪑 Estado Mesas
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Tab: Órdenes Activas -->
                <div id="content-orders" class="tab-content"
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Órdenes en Proceso</h3>
                            <button
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors"
                                onclick="switchTab('create')"
                            >
                                ➕ Nueva Orden
                            </button>
                        </div>
                        
                        @if($orders->count() > 0)
                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($orders as $order)
                                    <div class="border rounded-lg p-4 {{ 
                                        $order->status === 'pending' ? 'border-yellow-400 bg-yellow-50' : 
                                        ($order->status === 'preparing' ? 'border-blue-400 bg-blue-50' : 'border-green-400 bg-green-50') 
                                    }}">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-bold text-lg">{{ $order->order_number }}</h4>
                                                <p class="text-sm text-gray-600 flex items-center">
                                                    🪑 {{ $order->table->number ?? 'Sin mesa' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    🕒 {{ $order->created_at->format('H:i') }}
                                                </p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($order->status === 'preparing' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') 
                                            }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>

                                        <!-- Items del pedido -->
                                        <div class="space-y-1 mb-3 max-h-32 overflow-y-auto">
                                            @foreach($order->items as $item)
                                                <div class="flex justify-between text-sm bg-white rounded px-2 py-1">
                                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                                    <span class="font-medium text-green-600">
                                                        ${{ number_format($item->total_price, 2) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="border-t pt-3 flex justify-between items-center">
                                            <div class="font-bold text-lg text-green-600">
                                                ${{ number_format($order->total, 2) }}
                                            </div>
                                            <div class="flex space-x-2">
                                                @if($order->status === 'pending')
                                                    <a
                                                        href="{{ route('filament.resources.orders.edit', $order) }}"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium transition-colors"
                                                    >
                                                        👨‍🍳 Preparar
                                                    </a>
                                                @endif
                                                @if($order->status === 'preparing')
                                                    <a
                                                        href="{{ route('filament.resources.orders.edit', $order) }}"
                                                        class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-medium transition-colors"
                                                    >
                                                        ✅ Listo
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="text-6xl text-gray-300 mb-4">📋</div>
                                <h3 class="text-lg font-medium text-gray-600 mb-2">No hay órdenes activas</h3>
                                <p class="text-gray-500 mb-4">Crea tu primera orden usando el carrito</p>
                                <button
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-medium transition-colors"
                                    onclick="switchTab('create')"
                                >
                                    🛒 Crear Primera Orden
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tab: Crear Pedido -->
                <div id="content-create" class="tab-content hidden">
                    @livewire('order-cart')
                </div>

                <!-- Tab: Estado Mesas -->
                <div id="content-tables" class="tab-content hidden">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Estado de Mesas en Tiempo Real</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($tables as $table)
                                <div class="relative">
                                    <div class="border rounded-lg p-3 text-center cursor-pointer transition-all duration-200 hover:shadow-lg {{ 
                                        $table->status === 'available' ? 'border-green-400 bg-green-50' : 
                                        ($table->status === 'occupied' ? 'border-red-400 bg-red-50' : 'border-yellow-400 bg-yellow-50') 
                                    }}">
                                        <div class="text-2xl mb-2">
                                            @if($table->status === 'available')
                                                ✅
                                            @elseif($table->status === 'occupied')
                                                🔴
                                            @else
                                                ⚠️
                                            @endif
                                        </div>
                                        <div class="font-bold text-lg">{{ $table->number }}</div>
                                        <div class="text-sm text-gray-600">{{ $table->capacity }} personas</div>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                $table->status === 'available' ? 'bg-green-100 text-green-800' : 
                                                ($table->status === 'occupied' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') 
                                            }}">
                                                @if($table->status === 'available')
                                                    Disponible
                                                @elseif($table->status === 'occupied')
                                                    Ocupada
                                                @else
                                                    Reservada
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Remove active classes from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Activate selected tab
            const activeTab = document.getElementById(`tab-${tabId}`);
            const activeContent = document.getElementById(`content-${tabId}`);
            
            if (activeTab && activeContent) {
                activeTab.classList.add('bg-blue-50', 'border-blue-500', 'text-blue-600');
                activeTab.classList.remove('border-transparent', 'text-gray-500');
                activeContent.classList.remove('hidden');
            }
        }
    </script>
</x-filament::widget>

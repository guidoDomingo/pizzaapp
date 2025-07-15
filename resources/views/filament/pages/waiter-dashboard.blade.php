<x-filament::page>
    <div class="space-y-6" x-data="waiterDashboard()" x-init="init()">
        <!-- Encabezado de Meseros -->
        <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold">🍽️ Tablero de Meseros</h2>
                    <p class="text-green-100 text-lg">Centro de comando para entrega de pedidos</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">{{ $this->getReadyOrders()->count() + $this->getDeliveringOrders()->count() }}</div>
                    <div class="text-sm text-green-100">Pedidos para Entregar</div>
                    <div class="text-xs text-green-200 mt-1">
                        Actualizado: <span x-text="new Date().toLocaleTimeString()"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-green-100 border border-green-300 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $this->getReadyOrders()->count() }}</div>
                    <div class="text-sm text-green-800">✅ Listos</div>
                </div>
            </div>
            <div class="bg-blue-100 border border-blue-300 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $this->getDeliveringOrders()->count() }}</div>
                    <div class="text-sm text-blue-800">🚶‍♂️ En Entrega</div>
                </div>
            </div>
            <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ $this->getReadyOrders()->where('updated_at', '<=', now()->subMinutes(5))->count() }}
                    </div>
                    <div class="text-sm text-yellow-800">⚠️ Esperando +5min</div>
                </div>
            </div>
            <div class="bg-purple-100 border border-purple-300 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">
                        ${{ number_format(($this->getReadyOrders()->sum('total') + $this->getDeliveringOrders()->sum('total')), 2) }}
                    </div>
                    <div class="text-sm text-purple-800">💰 Valor Total</div>
                </div>
            </div>
        </div>

        <!-- Pestañas de Meseros -->
        <div class="bg-white rounded-lg shadow-lg">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button 
                        @click="activeTab = 'ready'" 
                        :class="activeTab === 'ready' ? 'bg-green-50 border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors"
                    >
                        ✅ LISTOS PARA ENTREGAR ({{ $this->getReadyOrders()->count() }})
                    </button>
                    <button 
                        @click="activeTab = 'delivering'" 
                        :class="activeTab === 'delivering' ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors"
                    >
                        🚶‍♂️ EN ENTREGA ({{ $this->getDeliveringOrders()->count() }})
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Pedidos Listos -->
                <div x-show="activeTab === 'ready'" x-transition>
                    @if($this->getReadyOrders()->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($this->getReadyOrders() as $order)
                                <div class="border-2 border-green-400 bg-green-50 rounded-lg p-5 relative transform hover:scale-105 transition-transform">
                                    <!-- Estado -->
                                    <div class="absolute -top-3 -right-3 {{ $order->updated_at->diffInMinutes() > 5 ? 'bg-red-600 animate-pulse' : 'bg-green-500' }} text-white text-xs px-3 py-1 rounded-full font-bold shadow-lg">
                                        @if($order->updated_at->diffInMinutes() > 5)
                                            🚨 URGENTE
                                        @else
                                            ✅ LISTO
                                        @endif
                                    </div>
                                    
                                    <!-- Info del Pedido -->
                                    <div class="mb-4">
                                        <h3 class="text-xl font-bold text-green-800 mb-2">{{ $order->order_number }}</h3>
                                        <div class="bg-white rounded-lg p-3 mb-3 shadow-sm">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="bg-green-100 px-3 py-1 rounded-full font-bold text-green-800">
                                                    🪑 Mesa {{ $order->table->number ?? 'N/A' }}
                                                </span>
                                                <span class="text-gray-600 text-sm">🕐 {{ $order->created_at->format('H:i') }}</span>
                                            </div>
                                            <div class="text-sm {{ $order->updated_at->diffInMinutes() > 5 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                                ✅ Listo desde: {{ $order->updated_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Resumen del Pedido -->
                                    <div class="bg-white rounded-lg p-4 mb-4 border border-green-200 shadow-sm">
                                        <div class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">📋 RESUMEN DEL PEDIDO:</div>
                                        <div class="space-y-2 max-h-32 overflow-y-auto">
                                            @foreach($order->items as $item)
                                                <div class="flex justify-between items-center py-1">
                                                    <span class="flex items-center">
                                                        <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs mr-2 font-bold">
                                                            {{ $item->quantity }}
                                                        </span>
                                                        <span class="font-medium">{{ $item->product->name }}</span>
                                                    </span>
                                                    <span class="text-green-600 font-bold">${{ number_format($item->total_price, 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="border-t border-green-200 mt-3 pt-3">
                                            <div class="flex justify-between font-bold text-lg text-green-700">
                                                <span>TOTAL:</span>
                                                <span>${{ number_format($order->total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información de Mesa -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                        <div class="text-sm font-bold text-blue-800 mb-1">
                                            📍 DESTINO: Mesa {{ $order->table->number ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-blue-600">
                                            Capacidad: {{ $order->table->capacity ?? 'N/A' }} personas
                                        </div>
                                    </div>

                                    <!-- Botón de Acción -->
                                    <button
                                        wire:click="startDelivering({{ $order->id }})"
                                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg"
                                    >
                                        🚶‍♂️ LLEVAR A MESA
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="text-8xl text-gray-300 mb-6">🍽️</div>
                            <h3 class="text-2xl font-bold text-gray-600 mb-4">No hay pedidos listos</h3>
                            <p class="text-gray-500 text-lg">Los pedidos terminados aparecerán aquí</p>
                        </div>
                    @endif
                </div>

                <!-- En Entrega -->
                <div x-show="activeTab === 'delivering'" x-transition>
                    @if($this->getDeliveringOrders()->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($this->getDeliveringOrders() as $order)
                                <div class="border-2 border-blue-400 bg-blue-50 rounded-lg p-5 relative">
                                    <!-- Estado -->
                                    <div class="absolute -top-3 -right-3 bg-blue-500 text-white text-xs px-3 py-1 rounded-full font-bold shadow-lg">
                                        🚶‍♂️ EN ENTREGA
                                    </div>
                                    
                                    <!-- Info del Pedido -->
                                    <div class="mb-4">
                                        <h3 class="text-xl font-bold text-blue-800 mb-2">{{ $order->order_number }}</h3>
                                        <div class="bg-white rounded-lg p-3 mb-3">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="bg-blue-100 px-3 py-1 rounded-full font-bold text-blue-800">
                                                    🪑 Mesa {{ $order->table->number ?? 'N/A' }}
                                                </span>
                                                <span class="text-gray-600 text-sm">🕐 {{ $order->created_at->format('H:i') }}</span>
                                            </div>
                                            <div class="text-sm text-blue-600 font-bold">
                                                🚶‍♂️ En entrega: {{ $order->updated_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Checklist de Entrega -->
                                    <div class="bg-yellow-100 border-l-4 border-yellow-400 p-4 mb-4 rounded">
                                        <div class="text-sm font-bold text-yellow-800 mb-2">
                                            ⚠️ VERIFICAR ANTES DE ENTREGAR:
                                        </div>
                                        <ul class="text-xs text-yellow-700 space-y-1">
                                            <li class="flex items-center">
                                                <span class="mr-2">☐</span>
                                                Todos los items del pedido
                                            </li>
                                            <li class="flex items-center">
                                                <span class="mr-2">☐</span>
                                                Servilletas y cubiertos
                                            </li>
                                            <li class="flex items-center">
                                                <span class="mr-2">☐</span>
                                                Condimentos necesarios
                                            </li>
                                            <li class="flex items-center">
                                                <span class="mr-2">☐</span>
                                                Bebidas si las hay
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Resumen del Pedido -->
                                    <div class="bg-white rounded-lg p-4 mb-4 border border-blue-200">
                                        <div class="text-sm font-bold text-gray-700 mb-3">📋 ITEMS A ENTREGAR:</div>
                                        <div class="space-y-2 max-h-32 overflow-y-auto">
                                            @foreach($order->items as $item)
                                                <div class="flex justify-between items-center py-1">
                                                    <span class="flex items-center">
                                                        <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs mr-2">
                                                            {{ $item->quantity }}
                                                        </span>
                                                        {{ $item->product->name }}
                                                    </span>
                                                    <span class="text-green-600 text-lg">✓</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Botón de Finalizar -->
                                    <button
                                        wire:click="markDelivered({{ $order->id }})"
                                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg"
                                    >
                                        ✅ CONFIRMAR ENTREGA
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="text-8xl text-gray-300 mb-6">🚶‍♂️</div>
                            <h3 class="text-2xl font-bold text-gray-600 mb-4">No hay entregas en curso</h3>
                            <p class="text-gray-500 text-lg">Los pedidos en entrega aparecerán aquí</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function waiterDashboard() {
            return {
                activeTab: 'ready',
                
                init() {
                    // Auto-refresh every 15 seconds
                    setInterval(() => {
                        Livewire.emit('$refresh');
                    }, 15000);
                }
            }
        }
    </script>
</x-filament::page>

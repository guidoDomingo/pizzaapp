<x-filament::page>
    <div class="space-y-6" x-data="kitchenDashboard()" x-init="init()">
        <!-- Encabezado de Cocina -->
        <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold">🔥 Tablero de Cocina</h2>
                    <p class="text-orange-100 text-lg">Centro de comando para preparación de pedidos</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold" x-text="pendingCount + preparingCount">{{ $this->getPendingOrders()->count() + $this->getPreparingOrders()->count() }}</div>
                    <div class="text-sm text-orange-100">Pedidos Activos en Cocina</div>
                    <div class="text-xs text-orange-200 mt-1">
                        Actualizado: <span x-text="new Date().toLocaleTimeString()"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-red-100 border border-red-300 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $this->getPendingOrders()->count() }}</div>
                    <div class="text-sm text-red-800">🔥 Pedidos Nuevos</div>
                </div>
            </div>
            <div class="bg-blue-100 border border-blue-300 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $this->getPreparingOrders()->count() }}</div>
                    <div class="text-sm text-blue-800">👨‍🍳 En Preparación</div>
                </div>
            </div>
            <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ $this->getPendingOrders()->where('created_at', '<=', now()->subMinutes(10))->count() }}
                    </div>
                    <div class="text-sm text-yellow-800">⚠️ Más de 10 min</div>
                </div>
            </div>
            <div class="bg-green-100 border border-green-300 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $this->getPreparingOrders()->sum(function($order) { return $order->items->sum('quantity'); }) }}
                    </div>
                    <div class="text-sm text-green-800">🍕 Items Cocinando</div>
                </div>
            </div>
        </div>

        <!-- Pestañas de Cocina -->
        <div class="bg-white rounded-lg shadow-lg">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button 
                        @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'bg-red-50 border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors"
                    >
                        🔥 PEDIDOS NUEVOS ({{ $this->getPendingOrders()->count() }})
                    </button>
                    <button 
                        @click="activeTab = 'preparing'" 
                        :class="activeTab === 'preparing' ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors"
                    >
                        👨‍🍳 EN PREPARACIÓN ({{ $this->getPreparingOrders()->count() }})
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Pedidos Nuevos -->
                <div x-show="activeTab === 'pending'" x-transition>
                    @if($this->getPendingOrders()->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($this->getPendingOrders() as $order)
                                <div class="border-2 border-red-400 bg-red-50 rounded-lg p-5 relative transform hover:scale-105 transition-transform">
                                    <!-- Urgencia indicator -->
                                    <div class="absolute -top-3 -right-3 {{ $order->created_at->diffInMinutes() > 10 ? 'bg-red-600 animate-pulse' : 'bg-red-500' }} text-white text-xs px-3 py-1 rounded-full font-bold shadow-lg">
                                        @if($order->created_at->diffInMinutes() > 15)
                                            🚨 URGENTE
                                        @elseif($order->created_at->diffInMinutes() > 10)
                                            ⏰ PRIORIDAD
                                        @else
                                            🔥 NUEVO
                                        @endif
                                    </div>
                                    
                                    <!-- Info del Pedido -->
                                    <div class="mb-4">
                                        <h3 class="text-xl font-bold text-red-800 mb-2">{{ $order->order_number }}</h3>
                                        <div class="flex justify-between items-center text-sm bg-white rounded p-2 mb-2">
                                            <span class="font-medium">🪑 Mesa {{ $order->table->number ?? 'N/A' }}</span>
                                            <span class="text-gray-600">🕐 {{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="text-sm {{ $order->created_at->diffInMinutes() > 10 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                            ⏱️ Hace {{ $order->created_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <!-- Items del Pedido -->
                                    <div class="space-y-2 mb-4 max-h-40 overflow-y-auto">
                                        @foreach($order->items as $item)
                                            <div class="bg-white rounded-lg p-3 border-l-4 border-red-400 shadow-sm">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <div class="font-bold text-sm mb-1">
                                                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs mr-2 font-bold">
                                                                {{ $item->quantity }}x
                                                            </span>
                                                            {{ $item->product->name }}
                                                        </div>
                                                        @if($item->notes)
                                                            <div class="text-xs text-gray-600 bg-yellow-100 p-1 rounded">
                                                                📝 {{ $item->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Notas especiales -->
                                    @if($order->notes)
                                        <div class="bg-yellow-100 border-l-4 border-yellow-400 p-3 mb-4 rounded">
                                            <div class="text-xs font-bold text-yellow-800">📋 NOTAS ESPECIALES:</div>
                                            <div class="text-sm text-yellow-700 font-medium">{{ $order->notes }}</div>
                                        </div>
                                    @endif

                                    <!-- Botón de Acción -->
                                    <button
                                        wire:click="startPreparing({{ $order->id }})"
                                        class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold py-4 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg"
                                    >
                                        🔥 COMENZAR PREPARACIÓN
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="text-8xl text-gray-300 mb-6">🍽️</div>
                            <h3 class="text-2xl font-bold text-gray-600 mb-4">¡Todo al día en cocina! 🎉</h3>
                            <p class="text-gray-500 text-lg">No hay pedidos nuevos esperando</p>
                        </div>
                    @endif
                </div>

                <!-- En Preparación -->
                <div x-show="activeTab === 'preparing'" x-transition>
                    @if($this->getPreparingOrders()->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($this->getPreparingOrders() as $order)
                                <div class="border-2 border-blue-400 bg-blue-50 rounded-lg p-5 relative">
                                    <!-- Estado -->
                                    <div class="absolute -top-3 -right-3 bg-blue-500 text-white text-xs px-3 py-1 rounded-full font-bold shadow-lg">
                                        👨‍🍳 EN COCINA
                                    </div>
                                    
                                    <!-- Info del Pedido -->
                                    <div class="mb-4">
                                        <h3 class="text-xl font-bold text-blue-800 mb-2">{{ $order->order_number }}</h3>
                                        <div class="flex justify-between items-center text-sm bg-white rounded p-2 mb-2">
                                            <span class="font-medium">🪑 Mesa {{ $order->table->number ?? 'N/A' }}</span>
                                            <span class="text-gray-600">🕐 {{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="text-sm text-blue-600 font-bold">
                                            👨‍🍳 Preparando: {{ $order->updated_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <!-- Items del Pedido -->
                                    <div class="space-y-2 mb-4 max-h-40 overflow-y-auto">
                                        @foreach($order->items as $item)
                                            <div class="bg-white rounded-lg p-3 border-l-4 border-blue-400 shadow-sm">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <div class="font-bold text-sm mb-1">
                                                            <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs mr-2">
                                                                {{ $item->quantity }}x
                                                            </span>
                                                            {{ $item->product->name }}
                                                        </div>
                                                        @if($item->notes)
                                                            <div class="text-xs text-gray-600 bg-yellow-100 p-1 rounded">
                                                                📝 {{ $item->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-green-600 text-lg">⏳</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Botón de Finalizar -->
                                    <button
                                        wire:click="markReady({{ $order->id }})"
                                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg"
                                    >
                                        ✅ MARCAR LISTO PARA ENTREGAR
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="text-8xl text-gray-300 mb-6">👨‍🍳</div>
                            <h3 class="text-2xl font-bold text-gray-600 mb-4">Cocina libre</h3>
                            <p class="text-gray-500 text-lg">No hay pedidos en preparación</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function kitchenDashboard() {
            return {
                activeTab: 'pending',
                pendingCount: {{ $this->getPendingOrders()->count() }},
                preparingCount: {{ $this->getPreparingOrders()->count() }},
                
                init() {
                    // Auto-refresh every 20 seconds
                    setInterval(() => {
                        Livewire.emit('$refresh');
                    }, 20000);
                    
                    // Play sound for new orders (optional)
                    this.checkForNewOrders();
                },
                
                checkForNewOrders() {
                    // Logic to check for new orders and play notification sound
                }
            }
        }
    </script>
</x-filament::page>

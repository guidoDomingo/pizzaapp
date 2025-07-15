<x-filament::widget>
    <div class="space-y-6">
        <!-- Encabezado de Cocina -->
        <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">👨‍🍳 Tablero de Cocina</h2>
                    <p class="text-orange-100">Gestiona los pedidos pendientes y en preparación</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $pendingOrders->count() + $preparingOrders->count() }}</div>
                    <div class="text-sm text-orange-100">Pedidos Activos</div>
                </div>
            </div>
        </div>

        <!-- Pestañas de Cocina -->
        <div class="bg-white rounded-lg shadow">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button 
                        onclick="switchKitchenTab('pending')" 
                        id="kitchen-tab-pending"
                        class="kitchen-tab-button bg-red-50 border-red-500 text-red-600 whitespace-nowrap py-3 px-6 border-b-2 font-medium text-sm"
                    >
                        🔥 Pedidos Nuevos ({{ $pendingOrders->count() }})
                    </button>
                    <button 
                        onclick="switchKitchenTab('preparing')" 
                        id="kitchen-tab-preparing"
                        class="kitchen-tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-6 border-b-2 font-medium text-sm"
                    >
                        👨‍🍳 En Preparación ({{ $preparingOrders->count() }})
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Pedidos Nuevos -->
                <div id="kitchen-content-pending" class="kitchen-tab-content">
                    @if($pendingOrders->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($pendingOrders as $order)
                                <div class="border-2 border-red-400 bg-red-50 rounded-lg p-4 relative">
                                    <!-- Urgencia -->
                                    <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                                        NUEVO
                                    </div>
                                    
                                    <!-- Info del Pedido -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-bold text-red-800">{{ $order->order_number }}</h3>
                                        <div class="flex justify-between items-center text-sm text-gray-600">
                                            <span>🪑 Mesa {{ $order->table->number ?? 'N/A' }}</span>
                                            <span>🕐 {{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Tiempo: {{ $order->created_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <!-- Items del Pedido -->
                                    <div class="space-y-2 mb-4">
                                        @foreach($order->items as $item)
                                            <div class="bg-white rounded p-3 border-l-4 border-red-400">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <div class="font-medium text-sm">
                                                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs mr-2">
                                                                {{ $item->quantity }}x
                                                            </span>
                                                            {{ $item->product->name }}
                                                        </div>
                                                        @if($item->notes)
                                                            <div class="text-xs text-gray-600 mt-1">
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
                                        <div class="bg-yellow-100 border border-yellow-400 rounded p-2 mb-4">
                                            <div class="text-xs font-medium text-yellow-800">📋 Notas Especiales:</div>
                                            <div class="text-sm text-yellow-700">{{ $order->notes }}</div>
                                        </div>
                                    @endif

                                    <!-- Botón de Acción -->
                                    <button
                                        wire:click="startPreparing({{ $order->id }})"
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg transition-colors"
                                    >
                                        🔥 Comenzar Preparación
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl text-gray-300 mb-4">🍽️</div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">No hay pedidos nuevos</h3>
                            <p class="text-gray-500">Los nuevos pedidos aparecerán aquí automáticamente</p>
                        </div>
                    @endif
                </div>

                <!-- En Preparación -->
                <div id="kitchen-content-preparing" class="kitchen-tab-content hidden">
                    @if($preparingOrders->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($preparingOrders as $order)
                                <div class="border-2 border-blue-400 bg-blue-50 rounded-lg p-4 relative">
                                    <!-- Estado -->
                                    <div class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                                        EN COCINA
                                    </div>
                                    
                                    <!-- Info del Pedido -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-bold text-blue-800">{{ $order->order_number }}</h3>
                                        <div class="flex justify-between items-center text-sm text-gray-600">
                                            <span>🪑 Mesa {{ $order->table->number ?? 'N/A' }}</span>
                                            <span>🕐 {{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="text-xs text-blue-600 mt-1 font-medium">
                                            ⏱️ En preparación: {{ $order->updated_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <!-- Items del Pedido -->
                                    <div class="space-y-2 mb-4">
                                        @foreach($order->items as $item)
                                            <div class="bg-white rounded p-3 border-l-4 border-blue-400">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <div class="font-medium text-sm">
                                                            <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs mr-2">
                                                                {{ $item->quantity }}x
                                                            </span>
                                                            {{ $item->product->name }}
                                                        </div>
                                                        @if($item->notes)
                                                            <div class="text-xs text-gray-600 mt-1">
                                                                📝 {{ $item->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Botón de Finalizar -->
                                    <button
                                        wire:click="markReady({{ $order->id }})"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition-colors"
                                    >
                                        ✅ Marcar Listo
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl text-gray-300 mb-4">👨‍🍳</div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">No hay pedidos en preparación</h3>
                            <p class="text-gray-500">Los pedidos que estés preparando aparecerán aquí</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchKitchenTab(tabId) {
            // Remove active classes from all tabs
            document.querySelectorAll('.kitchen-tab-button').forEach(button => {
                button.classList.remove('bg-red-50', 'border-red-500', 'text-red-600', 'bg-blue-50', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Hide all tab content
            document.querySelectorAll('.kitchen-tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Activate selected tab
            const activeTab = document.getElementById(`kitchen-tab-${tabId}`);
            const activeContent = document.getElementById(`kitchen-content-${tabId}`);
            
            if (activeTab && activeContent) {
                if (tabId === 'pending') {
                    activeTab.classList.add('bg-red-50', 'border-red-500', 'text-red-600');
                } else {
                    activeTab.classList.add('bg-blue-50', 'border-blue-500', 'text-blue-600');
                }
                activeTab.classList.remove('border-transparent', 'text-gray-500');
                activeContent.classList.remove('hidden');
            }
        }
        
        // Auto-refresh every 30 seconds
        setInterval(function() {
            Livewire.emit('refreshComponent');
        }, 30000);
    </script>
</x-filament::widget>

<x-filament::widget>
    <div class="space-y-6">
        <!-- Encabezado de Meseros -->
        <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">🍽️ Tablero de Meseros</h2>
                    <p class="text-green-100">Gestiona la entrega de pedidos listos</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $readyOrders->count() + $deliveringOrders->count() }}</div>
                    <div class="text-sm text-green-100">Pedidos para Entregar</div>
                </div>
            </div>
        </div>

        <!-- Pestañas de Meseros -->
        <div class="bg-white rounded-lg shadow">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button 
                        onclick="switchWaiterTab('ready')" 
                        id="waiter-tab-ready"
                        class="waiter-tab-button bg-green-50 border-green-500 text-green-600 whitespace-nowrap py-3 px-6 border-b-2 font-medium text-sm"
                    >
                        ✅ Listos para Entregar ({{ $readyOrders->count() }})
                    </button>
                    <button 
                        onclick="switchWaiterTab('delivering')" 
                        id="waiter-tab-delivering"
                        class="waiter-tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-6 border-b-2 font-medium text-sm"
                    >
                        🚶‍♂️ En Entrega ({{ $deliveringOrders->count() }})
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Pedidos Listos -->
                <div id="waiter-content-ready" class="waiter-tab-content">
                    @if($readyOrders->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($readyOrders as $order)
                                <div class="border-2 border-green-400 bg-green-50 rounded-lg p-4 relative">
                                    <!-- Estado -->
                                    <div class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-bold animate-pulse">
                                        LISTO
                                    </div>
                                    
                                    <!-- Info del Pedido -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-bold text-green-800">{{ $order->order_number }}</h3>
                                        <div class="flex justify-between items-center text-sm text-gray-600">
                                            <span class="bg-green-100 px-2 py-1 rounded font-medium">
                                                🪑 Mesa {{ $order->table->number ?? 'N/A' }}
                                            </span>
                                            <span>🕐 {{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="text-xs text-green-600 mt-1 font-medium">
                                            ✅ Listo desde: {{ $order->updated_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <!-- Resumen del Pedido -->
                                    <div class="bg-white rounded p-3 mb-4 border border-green-200">
                                        <div class="text-sm font-medium text-gray-700 mb-2">📋 Resumen del Pedido:</div>
                                        <div class="space-y-1">
                                            @foreach($order->items as $item)
                                                <div class="flex justify-between text-sm">
                                                    <span>
                                                        <span class="bg-green-500 text-white px-1.5 py-0.5 rounded-full text-xs mr-1">
                                                            {{ $item->quantity }}
                                                        </span>
                                                        {{ $item->product->name }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="border-t border-green-200 mt-2 pt-2">
                                            <div class="flex justify-between font-bold text-green-700">
                                                <span>Total:</span>
                                                <span>${{ number_format($order->total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información de Mesa -->
                                    <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-4">
                                        <div class="text-sm font-medium text-blue-800">
                                            📍 Mesa {{ $order->table->number ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-blue-600">
                                            Capacidad: {{ $order->table->capacity ?? 'N/A' }} personas
                                        </div>
                                    </div>

                                    <!-- Botón de Acción -->
                                    <button
                                        wire:click="startDelivering({{ $order->id }})"
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition-colors"
                                    >
                                        🚶‍♂️ Llevar a Mesa
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl text-gray-300 mb-4">🍽️</div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">No hay pedidos listos</h3>
                            <p class="text-gray-500">Los pedidos terminados por cocina aparecerán aquí</p>
                        </div>
                    @endif
                </div>

                <!-- En Entrega -->
                <div id="waiter-content-delivering" class="waiter-tab-content hidden">
                    @if($deliveringOrders->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($deliveringOrders as $order)
                                <div class="border-2 border-blue-400 bg-blue-50 rounded-lg p-4 relative">
                                    <!-- Estado -->
                                    <div class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                                        EN ENTREGA
                                    </div>
                                    
                                    <!-- Info del Pedido -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-bold text-blue-800">{{ $order->order_number }}</h3>
                                        <div class="flex justify-between items-center text-sm text-gray-600">
                                            <span class="bg-blue-100 px-2 py-1 rounded font-medium">
                                                🪑 Mesa {{ $order->table->number ?? 'N/A' }}
                                            </span>
                                            <span>🕐 {{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                        <div class="text-xs text-blue-600 mt-1 font-medium">
                                            🚶‍♂️ En entrega: {{ $order->updated_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <!-- Recordatorio -->
                                    <div class="bg-yellow-100 border border-yellow-400 rounded p-3 mb-4">
                                        <div class="text-sm font-medium text-yellow-800">
                                            ⚠️ Recordar verificar:
                                        </div>
                                        <ul class="text-xs text-yellow-700 mt-1 space-y-1">
                                            <li>• Todos los items del pedido</li>
                                            <li>• Servilletas y cubiertos</li>
                                            <li>• Condimentos necesarios</li>
                                        </ul>
                                    </div>

                                    <!-- Resumen del Pedido -->
                                    <div class="bg-white rounded p-3 mb-4 border border-blue-200">
                                        <div class="text-sm font-medium text-gray-700 mb-2">📋 Items Entregados:</div>
                                        <div class="space-y-1">
                                            @foreach($order->items as $item)
                                                <div class="flex justify-between text-sm">
                                                    <span>
                                                        <span class="bg-blue-500 text-white px-1.5 py-0.5 rounded-full text-xs mr-1">
                                                            {{ $item->quantity }}
                                                        </span>
                                                        {{ $item->product->name }}
                                                    </span>
                                                    <span class="text-green-600">✓</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Botón de Finalizar -->
                                    <button
                                        wire:click="markDelivered({{ $order->id }})"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition-colors"
                                    >
                                        ✅ Confirmar Entrega
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl text-gray-300 mb-4">🚶‍♂️</div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">No hay pedidos en entrega</h3>
                            <p class="text-gray-500">Los pedidos que estés entregando aparecerán aquí</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchWaiterTab(tabId) {
            // Remove active classes from all tabs
            document.querySelectorAll('.waiter-tab-button').forEach(button => {
                button.classList.remove('bg-green-50', 'border-green-500', 'text-green-600', 'bg-blue-50', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Hide all tab content
            document.querySelectorAll('.waiter-tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Activate selected tab
            const activeTab = document.getElementById(`waiter-tab-${tabId}`);
            const activeContent = document.getElementById(`waiter-content-${tabId}`);
            
            if (activeTab && activeContent) {
                if (tabId === 'ready') {
                    activeTab.classList.add('bg-green-50', 'border-green-500', 'text-green-600');
                } else {
                    activeTab.classList.add('bg-blue-50', 'border-blue-500', 'text-blue-600');
                }
                activeTab.classList.remove('border-transparent', 'text-gray-500');
                activeContent.classList.remove('hidden');
            }
        }
        
        // Auto-refresh every 15 seconds
        setInterval(function() {
            Livewire.emit('refreshComponent');
        }, 15000);
    </script>
</x-filament::widget>

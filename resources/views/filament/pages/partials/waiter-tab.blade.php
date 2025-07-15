<!-- Waiter Tab Content -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">🍽️ Panel de Meseros</h3>
        <div class="text-sm text-gray-500">
            Total para servir: {{ $this->getReadyOrders()->count() + $this->getDeliveringOrders()->count() }} órdenes
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Órdenes Listas -->
        <div class="bg-green-50 rounded-xl border border-green-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-green-800">✅ Listas para Servir</h4>
                <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $this->getReadyOrders()->count() }}
                </span>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($this->getReadyOrders() as $order)
                    @php
                        $waitingTime = $order->updated_at->diffInMinutes(now());
                        $urgentClass = $waitingTime > 10 ? 'border-red-300 bg-red-50' : 'border-green-200 bg-white';
                    @endphp
                    
                    <div class="rounded-lg border p-4 hover:shadow-md transition-shadow {{ $urgentClass }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-semibold text-gray-900">
                                    Orden #{{ $order->id }}
                                    @if($waitingTime > 10)
                                        <span class="text-red-600 text-xs ml-2">🚨 URGENTE</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    Mesa {{ $order->table->number ?? 'N/A' }} • 
                                    Lista hace {{ $waitingTime }} min
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600">
                                    ${{ number_format($order->total, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Terminado: {{ $order->updated_at->format('H:i') }}
                                </div>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-3 space-y-1">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">
                                        {{ $item->quantity }}x {{ $item->product->name }}
                                    </span>
                                    <span class="text-gray-600">
                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Action Button -->
                        <button 
                            wire:click="startDelivering({{ $order->id }})"
                            class="w-full {{ $waitingTime > 10 ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            🚶‍♂️ Iniciar Entrega
                        </button>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">🎉</div>
                        <div>No hay órdenes listas</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Órdenes en Entrega -->
        <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-yellow-800">🚶‍♂️ En Entrega</h4>
                <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $this->getDeliveringOrders()->count() }}
                </span>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($this->getDeliveringOrders() as $order)
                    @php
                        $deliveryTime = $order->updated_at->diffInMinutes(now());
                    @endphp
                    
                    <div class="bg-white rounded-lg border border-yellow-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-semibold text-gray-900">
                                    Orden #{{ $order->id }}
                                    @if($order->employee_id)
                                        <span class="text-yellow-600 text-xs ml-2">👤 Asignado</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    Mesa {{ $order->table->number ?? 'N/A' }} • 
                                    En entrega {{ $deliveryTime }} min
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-yellow-600">
                                    ${{ number_format($order->total, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Enviado: {{ $order->updated_at->format('H:i') }}
                                </div>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-3 space-y-1">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">
                                        {{ $item->quantity }}x {{ $item->product->name }}
                                    </span>
                                    <span class="text-gray-600">
                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Action Button -->
                        <button 
                            wire:click="markDelivered({{ $order->id }})"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            ✅ Marcar Como Entregado
                        </button>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">🍽️</div>
                        <div>No hay órdenes en entrega</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Stats de Meseros -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">📊 Estadísticas de Servicio</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $this->getReadyOrders()->count() }}</div>
                <div class="text-sm text-gray-600">Para servir</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $this->getDeliveringOrders()->count() }}</div>
                <div class="text-sm text-gray-600">En entrega</div>
            </div>
            <div class="text-center">
                @php
                    $avgWaitTime = $this->getReadyOrders()->avg(function($order) {
                        return $order->updated_at->diffInMinutes(now());
                    });
                @endphp
                <div class="text-2xl font-bold text-purple-600">{{ round($avgWaitTime ?? 0) }}min</div>
                <div class="text-sm text-gray-600">Tiempo de espera</div>
            </div>
            <div class="text-center">
                @php
                    $urgentOrders = $this->getReadyOrders()->filter(function($order) {
                        return $order->updated_at->diffInMinutes(now()) > 10;
                    })->count();
                @endphp
                <div class="text-2xl font-bold {{ $urgentOrders > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $urgentOrders }}</div>
                <div class="text-sm text-gray-600">Urgentes</div>
            </div>
        </div>
    </div>

    <!-- Guía Rápida -->
    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
        <h4 class="text-lg font-semibold text-blue-800 mb-3">💡 Guía Rápida</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <div class="font-medium text-blue-700 mb-2">🟢 Órdenes Normales:</div>
                <ul class="text-blue-600 space-y-1">
                    <li>• Servir en orden de llegada</li>
                    <li>• Verificar mesa antes de entregar</li>
                    <li>• Confirmar todos los items</li>
                </ul>
            </div>
            <div>
                <div class="font-medium text-red-700 mb-2">🔴 Órdenes Urgentes (>10min):</div>
                <ul class="text-red-600 space-y-1">
                    <li>• Prioridad máxima</li>
                    <li>• Entregar inmediatamente</li>
                    <li>• Disculparse por la demora</li>
                </ul>
            </div>
        </div>
    </div>
</div>

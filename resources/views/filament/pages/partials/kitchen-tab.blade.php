<!-- Kitchen Tab Content -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">🔥 Panel de Cocina</h3>
        <div class="text-sm text-gray-500">
            Total en cocina: {{ $this->getPendingOrders()->count() + $this->getPreparingOrders()->count() }} órdenes
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Órdenes Pendientes -->
        <div class="bg-orange-50 rounded-xl border border-orange-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-orange-800">⏳ Pendientes de Preparar</h4>
                <span class="bg-orange-200 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $this->getPendingOrders()->count() }}
                </span>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($this->getPendingOrders() as $order)
                    <div class="bg-white rounded-lg border border-orange-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-semibold text-gray-900">
                                    Orden #{{ $order->id }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    Mesa {{ $order->table->number ?? 'N/A' }} • 
                                    {{ $order->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-orange-600">
                                    ${{ number_format($order->total, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $order->created_at->format('H:i') }}
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
                            wire:click="startPreparing({{ $order->id }})"
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            🔥 Iniciar Preparación
                        </button>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">🎉</div>
                        <div>No hay órdenes pendientes</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Órdenes en Preparación -->
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-blue-800">👨‍🍳 En Preparación</h4>
                <span class="bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $this->getPreparingOrders()->count() }}
                </span>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($this->getPreparingOrders() as $order)
                    @php
                        $minutesAgo = $order->updated_at->diffInMinutes(now());
                        $urgentClass = $minutesAgo > 15 ? 'border-red-300 bg-red-50' : 'border-blue-200 bg-white';
                    @endphp
                    
                    <div class="rounded-lg border p-4 hover:shadow-md transition-shadow {{ $urgentClass }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-semibold text-gray-900">
                                    Orden #{{ $order->id }}
                                    @if($minutesAgo > 15)
                                        <span class="text-red-600 text-xs ml-2">⚠️ URGENTE</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    Mesa {{ $order->table->number ?? 'N/A' }} • 
                                    En preparación {{ $minutesAgo }} min
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-blue-600">
                                    ${{ number_format($order->total, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Iniciado: {{ $order->updated_at->format('H:i') }}
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
                            wire:click="markReady({{ $order->id }})"
                            class="w-full {{ $minutesAgo > 15 ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            ✅ Marcar Como Lista
                        </button>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">👨‍🍳</div>
                        <div>No hay órdenes en preparación</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Stats de Cocina -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">📊 Estadísticas de Cocina</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $this->getPendingOrders()->count() }}</div>
                <div class="text-sm text-gray-600">En cola</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $this->getPreparingOrders()->count() }}</div>
                <div class="text-sm text-gray-600">Preparando</div>
            </div>
            <div class="text-center">
                @php
                    $avgPrepTime = $this->getPreparingOrders()->avg(function($order) {
                        return $order->updated_at->diffInMinutes(now());
                    });
                @endphp
                <div class="text-2xl font-bold text-purple-600">{{ round($avgPrepTime ?? 0) }}min</div>
                <div class="text-sm text-gray-600">Tiempo promedio</div>
            </div>
        </div>
    </div>
</div>

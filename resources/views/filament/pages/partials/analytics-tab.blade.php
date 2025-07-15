<!-- Analytics Tab Content -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">📊 Análisis y Reportes</h3>
        <div class="text-sm text-gray-500">
            Actualizado: {{ now()->format('H:i:s') }}
        </div>
    </div>

    @php
        $analytics = $this->getAnalyticsData();
    @endphp

    <!-- Métricas Principales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Órdenes del Día -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Órdenes Hoy</p>
                    <p class="text-3xl font-bold">{{ $analytics['daily_orders'] }}</p>
                </div>
                <div class="bg-blue-400 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Órdenes Semanales -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Órdenes (7 días)</p>
                    <p class="text-3xl font-bold">{{ $analytics['weekly_orders'] }}</p>
                </div>
                <div class="bg-green-400 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Promedio Diario -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Promedio Diario</p>
                    <p class="text-3xl font-bold">{{ round($analytics['weekly_orders'] / 7, 1) }}</p>
                </div>
                <div class="bg-purple-400 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3-3-3a3 3 0 110-6z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Más Populares -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">🍕 Productos Más Populares Hoy</h4>
        
        @if($analytics['most_popular_products']->count() > 0)
            <div class="space-y-3">
                @foreach($analytics['most_popular_products'] as $index => $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-sm text-gray-600">${{ number_format($product->price, 2) }} c/u</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">{{ $product->order_items_count }} vendidos</div>
                            <div class="text-sm text-gray-600">
                                ${{ number_format($product->price * $product->order_items_count, 2) }} total
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <div class="text-4xl mb-2">📊</div>
                <div>No hay datos de productos para hoy</div>
            </div>
        @endif
    </div>

    <!-- Horas Pico -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">⏰ Análisis de Horas Pico</h4>
        
        @if($analytics['busiest_hours']->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gráfico Simple -->
                <div>
                    <h5 class="text-md font-medium text-gray-700 mb-3">Órdenes por Hora</h5>
                    <div class="space-y-2">
                        @php
                            $maxOrders = $analytics['busiest_hours']->max('count');
                        @endphp
                        @foreach($analytics['busiest_hours'] as $hour)
                            @php
                                $percentage = $maxOrders > 0 ? ($hour->count / $maxOrders) * 100 : 0;
                                $timeLabel = sprintf('%02d:00', $hour->hour);
                            @endphp
                            <div class="flex items-center">
                                <div class="w-12 text-sm text-gray-600">{{ $timeLabel }}</div>
                                <div class="flex-1 mx-3">
                                    <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                        <div class="bg-blue-500 h-full transition-all duration-300" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="w-8 text-sm font-medium text-gray-900">{{ $hour->count }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Estadísticas de Horas -->
                <div>
                    <h5 class="text-md font-medium text-gray-700 mb-3">Resumen</h5>
                    @php
                        $peakHour = $analytics['busiest_hours']->sortByDesc('count')->first();
                        $quietHour = $analytics['busiest_hours']->sortBy('count')->first();
                    @endphp
                    
                    <div class="space-y-3">
                        @if($peakHour)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <div class="text-sm font-medium text-red-800">Hora Pico</div>
                                <div class="text-lg font-bold text-red-900">
                                    {{ sprintf('%02d:00', $peakHour->hour) }} 
                                    ({{ $peakHour->count }} órdenes)
                                </div>
                            </div>
                        @endif
                        
                        @if($quietHour)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="text-sm font-medium text-green-800">Hora Más Tranquila</div>
                                <div class="text-lg font-bold text-green-900">
                                    {{ sprintf('%02d:00', $quietHour->hour) }} 
                                    ({{ $quietHour->count }} órdenes)
                                </div>
                            </div>
                        @endif
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="text-sm font-medium text-blue-800">Promedio por Hora</div>
                            <div class="text-lg font-bold text-blue-900">
                                {{ round($analytics['busiest_hours']->avg('count'), 1) }} órdenes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <div class="text-4xl mb-2">⏰</div>
                <div>No hay datos de horarios para hoy</div>
            </div>
        @endif
    </div>

    <!-- Rendimiento Operacional -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Eficiencia de Cocina -->
        <div class="bg-orange-50 rounded-xl border border-orange-200 p-6">
            <h4 class="text-lg font-semibold text-orange-800 mb-4">👨‍🍳 Eficiencia de Cocina</h4>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-orange-700">Órdenes Pendientes:</span>
                    <span class="font-bold text-orange-800">{{ $stats['pending'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-orange-700">En Preparación:</span>
                    <span class="font-bold text-orange-800">{{ $stats['preparing'] }}</span>
                </div>
                <div class="flex justify-between border-t border-orange-200 pt-2">
                    <span class="text-orange-700">Total en Cocina:</span>
                    <span class="font-bold text-orange-800">{{ $stats['pending'] + $stats['preparing'] }}</span>
                </div>
                @php
                    $kitchenLoad = (($stats['pending'] + $stats['preparing']) / max($analytics['daily_orders'], 1)) * 100;
                @endphp
                <div class="mt-3">
                    <div class="text-sm text-orange-700 mb-1">Carga de Trabajo: {{ round($kitchenLoad) }}%</div>
                    <div class="bg-orange-200 rounded-full h-3">
                        <div class="bg-orange-500 h-full rounded-full transition-all" style="width: {{ min($kitchenLoad, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eficiencia de Servicio -->
        <div class="bg-green-50 rounded-xl border border-green-200 p-6">
            <h4 class="text-lg font-semibold text-green-800 mb-4">🍽️ Eficiencia de Servicio</h4>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-green-700">Listas para Servir:</span>
                    <span class="font-bold text-green-800">{{ $stats['ready'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-green-700">En Entrega:</span>
                    <span class="font-bold text-green-800">{{ $stats['delivering'] }}</span>
                </div>
                <div class="flex justify-between border-t border-green-200 pt-2">
                    <span class="text-green-700">Total Servicio:</span>
                    <span class="font-bold text-green-800">{{ $stats['ready'] + $stats['delivering'] }}</span>
                </div>
                @php
                    $serviceLoad = (($stats['ready'] + $stats['delivering']) / max($analytics['daily_orders'], 1)) * 100;
                @endphp
                <div class="mt-3">
                    <div class="text-sm text-green-700 mb-1">Carga de Trabajo: {{ round($serviceLoad) }}%</div>
                    <div class="bg-green-200 rounded-full h-3">
                        <div class="bg-green-500 h-full rounded-full transition-all" style="width: {{ min($serviceLoad, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overview Tab Content -->
<div class="space-y-6">
    <!-- Estadísticas Detalladas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Órdenes del Día -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Órdenes Hoy</p>
                    <p class="text-3xl font-bold text-blue-800">{{ $stats['orders_today'] }}</p>
                </div>
                <div class="p-3 bg-blue-200 rounded-full">
                    <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ventas -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Ventas Diarias</p>
                    <p class="text-3xl font-bold text-green-800">${{ number_format($stats['daily_sales'], 0) }}</p>
                </div>
                <div class="p-3 bg-green-200 rounded-full">
                    <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Órdenes Activas -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Órdenes Activas</p>
                    <p class="text-3xl font-bold text-purple-800">{{ $stats['active_orders'] }}</p>
                </div>
                <div class="p-3 bg-purple-200 rounded-full">
                    <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Mesas Disponibles -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600">Mesas Libres</p>
                    <p class="text-3xl font-bold text-orange-800">{{ $stats['available_tables'] }}/{{ $stats['total_tables'] }}</p>
                </div>
                <div class="p-3 bg-orange-200 rounded-full">
                    <svg class="w-8 h-8 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado del Flujo de Trabajo -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔄 Flujo de Trabajo Actual</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Pendientes -->
            <div class="bg-orange-50 rounded-lg p-4 border-2 border-orange-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['pending'] }}</div>
                    <div class="text-sm text-orange-700 font-medium">Pendientes</div>
                    <div class="text-xs text-orange-600 mt-1">Esperando cocina</div>
                </div>
            </div>

            <!-- En Preparación -->
            <div class="bg-blue-50 rounded-lg p-4 border-2 border-blue-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['preparing'] }}</div>
                    <div class="text-sm text-blue-700 font-medium">Preparando</div>
                    <div class="text-xs text-blue-600 mt-1">En cocina</div>
                </div>
            </div>

            <!-- Listas -->
            <div class="bg-green-50 rounded-lg p-4 border-2 border-green-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['ready'] }}</div>
                    <div class="text-sm text-green-700 font-medium">Listas</div>
                    <div class="text-xs text-green-600 mt-1">Para servir</div>
                </div>
            </div>

            <!-- Entregando -->
            <div class="bg-yellow-50 rounded-lg p-4 border-2 border-yellow-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['delivering'] }}</div>
                    <div class="text-sm text-yellow-700 font-medium">Entregando</div>
                    <div class="text-xs text-yellow-600 mt-1">En camino</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen Rápido -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Alertas -->
        <div class="bg-red-50 rounded-xl border border-red-200 p-6">
            <h4 class="text-lg font-semibold text-red-800 mb-3">⚠️ Atención Requerida</h4>
            @if($stats['pending_payments'] > 0)
                <div class="mb-2">
                    <span class="text-red-600 font-medium">{{ $stats['pending_payments'] }}</span>
                    <span class="text-red-700"> órdenes sin pagar</span>
                </div>
            @endif
            @if($stats['pending'] > 5)
                <div class="mb-2">
                    <span class="text-red-600 font-medium">{{ $stats['pending'] }}</span>
                    <span class="text-red-700"> órdenes esperando cocina</span>
                </div>
            @endif
            @if($stats['ready'] > 3)
                <div class="mb-2">
                    <span class="text-red-600 font-medium">{{ $stats['ready'] }}</span>
                    <span class="text-red-700"> órdenes listas sin servir</span>
                </div>
            @endif
            @if($stats['pending_payments'] == 0 && $stats['pending'] <= 5 && $stats['ready'] <= 3)
                <div class="text-green-700">✅ Todo bajo control</div>
            @endif
        </div>

        <!-- Estado de Mesas -->
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
            <h4 class="text-lg font-semibold text-blue-800 mb-3">🪑 Estado de Mesas</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-blue-700">Disponibles:</span>
                    <span class="font-medium text-blue-800">{{ $stats['available_tables'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Ocupadas:</span>
                    <span class="font-medium text-blue-800">{{ $stats['total_tables'] - $stats['available_tables'] }}</span>
                </div>
                <div class="flex justify-between border-t border-blue-200 pt-2">
                    <span class="text-blue-700 font-medium">Total:</span>
                    <span class="font-bold text-blue-800">{{ $stats['total_tables'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

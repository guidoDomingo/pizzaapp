<x-filament::page>
    <div class="space-y-6">
        <!-- Header con estadísticas principales -->
        <div class="bg-gradient-to-r from-blue-500 via-purple-600 to-indigo-700 rounded-xl shadow-xl p-6 text-white">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">🎯 Centro de Operaciones</h1>
                <div class="text-sm opacity-90">
                    {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white/10 backdrop-blur rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-orange-300">{{ $this->stats['pending'] }}</div>
                    <div class="text-xs text-blue-200">Pendientes</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-300">{{ $this->stats['preparing'] }}</div>
                    <div class="text-xs text-blue-200">Preparando</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-300">{{ $this->stats['ready'] }}</div>
                    <div class="text-xs text-blue-200">Listas</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-300">{{ $this->stats['delivering'] }}</div>
                    <div class="text-xs text-blue-200">Entregando</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-emerald-300">${{ number_format($this->stats['daily_sales'], 0) }}</div>
                    <div class="text-xs text-blue-200">Ventas Hoy</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-cyan-300">{{ $this->stats['available_tables'] }}/{{ $this->stats['total_tables'] }}</div>
                    <div class="text-xs text-blue-200">Mesas</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-wrap gap-4 mb-6">
                <a href="{{ route('order.cart') }}" target="_blank"
                   class="inline-flex items-center bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Crear Nuevo Pedido
                </a>
                <a href="/admin/orders" target="_blank"
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Ver Órdenes
                </a>
                <a href="/admin/products" target="_blank"
                   class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 inline-flex items-center">
                    🍕 Productos
                </a>
                <a href="/admin/tables" target="_blank"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 inline-flex items-center">
                    🪑 Mesas
                </a>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="flex border-b border-gray-200">
                <button 
                    wire:click="setActiveTab('overview')"
                    class="flex-1 px-6 py-4 text-center font-medium transition-all {{ $activeTab === 'overview' ? 'bg-purple-50 text-purple-700 border-b-2 border-purple-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}"
                >
                    🎯 Resumen General
                </button>
                <button 
                    wire:click="setActiveTab('kitchen')"
                    class="flex-1 px-6 py-4 text-center font-medium transition-all {{ $activeTab === 'kitchen' ? 'bg-red-50 text-red-700 border-b-2 border-red-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}"
                >
                    🔥 Cocina ({{ $this->getPendingOrders()->count() + $this->getPreparingOrders()->count() }})
                </button>
                <button 
                    wire:click="setActiveTab('waiter')"
                    class="flex-1 px-6 py-4 text-center font-medium transition-all {{ $activeTab === 'waiter' ? 'bg-green-50 text-green-700 border-b-2 border-green-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}"
                >
                    🍽️ Meseros ({{ $this->getReadyOrders()->count() + $this->getDeliveringOrders()->count() }})
                </button>
                <button 
                    wire:click="setActiveTab('analytics')"
                    class="flex-1 px-6 py-4 text-center font-medium transition-all {{ $activeTab === 'analytics' ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}"
                >
                    📊 Análisis
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                @if($activeTab === 'overview')
                    @include('filament.pages.partials.overview-tab', ['stats' => $stats ?? $this->stats])
                @elseif($activeTab === 'kitchen')
                    @include('filament.pages.partials.kitchen-tab')
                @elseif($activeTab === 'waiter')
                    @include('filament.pages.partials.waiter-tab')
                @elseif($activeTab === 'analytics')
                    @include('filament.pages.partials.analytics-tab')
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-refresh cada 30 segundos
        setInterval(() => {
            Livewire.emit('refreshData');
        }, 30000);

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.altKey) {
                switch(e.key) {
                    case '1':
                        e.preventDefault();
                        @this.setActiveTab('overview');
                        break;
                    case '2':
                        e.preventDefault();
                        @this.setActiveTab('kitchen');
                        break;
                    case '3':
                        e.preventDefault();
                        @this.setActiveTab('waiter');
                        break;
                    case '4':
                        e.preventDefault();
                        @this.setActiveTab('analytics');
                        break;
                }
            }
        });
    </script>

    <style>
        @keyframes pulse-order {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .order-card-new {
            animation: pulse-order 2s infinite;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }
        
        [wire\\:loading] {
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
    </style>
    @endpush

    <!-- Auto refresh -->
    <div wire:poll.30s="refreshData" class="hidden"></div>
</x-filament::page>

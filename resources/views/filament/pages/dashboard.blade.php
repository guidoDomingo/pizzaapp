<x-filament::page>
    <style>
        .dashboard-container {
            padding: 1.5rem;
            space-y: 1.5rem;
        }
        
        .dashboard-container > * + * {
            margin-top: 1.5rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #3b82f6 0%, #9333ea 50%, #4338ca 100%);
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            color: white;
        }
        
        .hero-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .hero-title {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
        }
        
        .hero-date {
            font-size: 0.875rem;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        .action-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            padding: 2rem;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .btn-green {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: white;
        }
        
        .btn-blue {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
        }
        
        .btn-purple {
            background: linear-gradient(135deg, #9333ea, #7c3aed);
            color: white;
        }
        
        .btn-indigo {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: white;
        }
        
        .btn-icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
        }
        
        .tabs-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .tabs-header {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .tab-button {
            flex: 1;
            padding: 1rem 1.5rem;
            text-align: center;
            font-weight: 600;
            border: none;
            background: none;
            cursor: pointer;
            transition: all 0.2s;
            color: #6b7280;
        }
        
        .tab-button:hover {
            background: #f9fafb;
            color: #374151;
        }
        
        .tab-button.active-overview {
            background: #fdf4ff;
            color: #7c3aed;
            border-bottom: 2px solid #7c3aed;
        }
        
        .tab-button.active-kitchen {
            background: #fef2f2;
            color: #dc2626;
            border-bottom: 2px solid #dc2626;
        }
        
        .tab-button.active-waiter {
            background: #f0fdf4;
            color: #16a34a;
            border-bottom: 2px solid #16a34a;
        }
        
        .tab-button.active-analytics {
            background: #eff6ff;
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
        }
        
        .tab-content {
            padding: 2rem;
        }
        
        .overview-section {
            margin-bottom: 2rem;
        }
        
        .overview-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #374151;
        }
        
        .overview-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .text-orange { color: #ea580c; }
        .text-blue { color: #2563eb; }
        .text-green { color: #16a34a; }
        .text-yellow { color: #ca8a04; }
        .text-emerald { color: #059669; }
        .text-cyan { color: #0891b2; }
    </style>
    
    <div class="dashboard-container">
        <!-- Header con estadísticas principales -->
        <div class="hero-section">
            <div class="hero-header">
                <h1 class="hero-title">🎯 Centro de Operaciones</h1>
                <div class="hero-date">
                    {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number text-orange">{{ $this->stats['pending'] }}</div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-blue">{{ $this->stats['preparing'] }}</div>
                    <div class="stat-label">Preparando</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-green">{{ $this->stats['ready'] }}</div>
                    <div class="stat-label">Listas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-yellow">{{ $this->stats['delivering'] }}</div>
                    <div class="stat-label">Entregando</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-emerald">${{ number_format($this->stats['daily_sales'], 0) }}</div>
                    <div class="stat-label">Ventas Hoy</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-cyan">{{ $this->stats['available_tables'] }}/{{ $this->stats['total_tables'] }}</div>
                    <div class="stat-label">Mesas</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('order.cart') }}" target="_blank" class="btn btn-green">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Crear Nuevo Pedido
                </a>
                <a href="/admin/orders" target="_blank" class="btn btn-blue">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Ver Órdenes
                </a>
                <a href="/admin/products" target="_blank" class="btn btn-purple">
                    🍕 Productos
                </a>
                <a href="/admin/tables" target="_blank" class="btn btn-indigo">
                    🪑 Mesas
                </a>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="tabs-container">
            <div class="tabs-header">
                <button 
                    wire:click="setActiveTab('overview')"
                    class="tab-button {{ $activeTab === 'overview' ? 'active-overview' : '' }}"
                >
                    🎯 Resumen General
                </button>
                <button 
                    wire:click="setActiveTab('kitchen')"
                    class="tab-button {{ $activeTab === 'kitchen' ? 'active-kitchen' : '' }}"
                >
                    🔥 Cocina ({{ $this->getPendingOrders()->count() + $this->getPreparingOrders()->count() }})
                </button>
                <button 
                    wire:click="setActiveTab('waiter')"
                    class="tab-button {{ $activeTab === 'waiter' ? 'active-waiter' : '' }}"
                >
                    🍽️ Meseros ({{ $this->getReadyOrders()->count() + $this->getDeliveringOrders()->count() }})
                </button>
                <button 
                    wire:click="setActiveTab('analytics')"
                    class="tab-button {{ $activeTab === 'analytics' ? 'active-analytics' : '' }}"
                >
                    📊 Análisis
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
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
    <div wire:poll.30s="refreshData" style="display: none;"></div>
</x-filament::page>

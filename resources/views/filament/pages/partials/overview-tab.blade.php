<!-- Overview Tab Content -->
<style>
    .overview-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .stats-detailed-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .stat-card-detailed {
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-detailed::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.1;
        z-index: 0;
    }
    
    .stat-card-detailed > * {
        position: relative;
        z-index: 1;
    }
    
    .stat-card-blue {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-color: #93c5fd;
    }
    
    .stat-card-green {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border-color: #86efac;
    }
    
    .stat-card-purple {
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
        border-color: #c4b5fd;
    }
    
    .stat-card-orange {
        background: linear-gradient(135deg, #fed7aa, #fdba74);
        border-color: #fb923c;
    }
    
    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-icon-container {
        padding: 0.75rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon-blue { background-color: #93c5fd; }
    .stat-icon-green { background-color: #86efac; }
    .stat-icon-purple { background-color: #c4b5fd; }
    .stat-icon-orange { background-color: #fb923c; }
    
    .stat-icon {
        width: 2rem;
        height: 2rem;
    }
    
    .stat-title {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
    }
    
    .text-blue-600 { color: #2563eb; }
    .text-blue-700 { color: #1d4ed8; }
    .text-blue-800 { color: #1e40af; }
    .text-green-600 { color: #16a34a; }
    .text-green-700 { color: #15803d; }
    .text-green-800 { color: #166534; }
    .text-purple-600 { color: #9333ea; }
    .text-purple-700 { color: #7c3aed; }
    .text-purple-800 { color: #6b21a8; }
    .text-orange-600 { color: #ea580c; }
    .text-orange-700 { color: #c2410c; }
    .text-orange-800 { color: #9a3412; }
    
    .workflow-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        padding: 2rem;
    }
    
    .workflow-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1.5rem;
    }
    
    .workflow-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .workflow-card {
        padding: 1rem;
        border-radius: 8px;
        border: 2px solid;
        text-align: center;
    }
    
    .workflow-card-orange {
        background: #fff7ed;
        border-color: #fed7aa;
    }
    
    .workflow-card-blue {
        background: #eff6ff;
        border-color: #93c5fd;
    }
    
    .workflow-card-green {
        background: #f0fdf4;
        border-color: #86efac;
    }
    
    .workflow-card-yellow {
        background: #fefce8;
        border-color: #fde047;
    }
    
    .workflow-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .workflow-label {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .workflow-subtitle {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .alert-section {
        background: #fef2f2;
        border-radius: 12px;
        border: 1px solid #fecaca;
        padding: 1.5rem;
    }
    
    .alert-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #991b1b;
        margin-bottom: 1rem;
    }
    
    .alert-item {
        margin-bottom: 0.5rem;
    }
    
    .alert-number {
        color: #dc2626;
        font-weight: 500;
    }
    
    .alert-text {
        color: #b91c1c;
    }
    
    .success-text {
        color: #15803d;
    }
    
    .tables-section {
        background: #eff6ff;
        border-radius: 12px;
        border: 1px solid #93c5fd;
        padding: 1.5rem;
    }
    
    .tables-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 1rem;
    }
    
    .tables-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    
    .tables-row:last-child {
        border-top: 1px solid #93c5fd;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .tables-label {
        color: #1d4ed8;
    }
    
    .tables-value {
        font-weight: 500;
        color: #1e40af;
    }
    
    .tables-total {
        font-weight: 700;
        color: #1e40af;
    }
</style>

<div class="overview-container">
    <!-- Estadísticas Detalladas -->
    <div class="stats-detailed-grid">
        <!-- Órdenes del Día -->
        <div class="stat-card-detailed stat-card-blue">
            <div class="stat-card-header">
                <div>
                    <p class="stat-title text-blue-600">Órdenes Hoy</p>
                    <p class="stat-value text-blue-800">{{ $stats['orders_today'] }}</p>
                </div>
                <div class="stat-icon-container stat-icon-blue">
                    <svg class="stat-icon text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ventas -->
        <div class="stat-card-detailed stat-card-green">
            <div class="stat-card-header">
                <div>
                    <p class="stat-title text-green-600">Ventas Diarias</p>
                    <p class="stat-value text-green-800">${{ number_format($stats['daily_sales'], 0) }}</p>
                </div>
                <div class="stat-icon-container stat-icon-green">
                    <svg class="stat-icon text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Órdenes Activas -->
        <div class="stat-card-detailed stat-card-purple">
            <div class="stat-card-header">
                <div>
                    <p class="stat-title text-purple-600">Órdenes Activas</p>
                    <p class="stat-value text-purple-800">{{ $stats['active_orders'] }}</p>
                </div>
                <div class="stat-icon-container stat-icon-purple">
                    <svg class="stat-icon text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Mesas Disponibles -->
        <div class="stat-card-detailed stat-card-orange">
            <div class="stat-card-header">
                <div>
                    <p class="stat-title text-orange-600">Mesas Libres</p>
                    <p class="stat-value text-orange-800">{{ $stats['available_tables'] }}/{{ $stats['total_tables'] }}</p>
                </div>
                <div class="stat-icon-container stat-icon-orange">
                    <svg class="stat-icon text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado del Flujo de Trabajo -->
    <div class="workflow-section">
        <h3 class="workflow-title">🔄 Flujo de Trabajo Actual</h3>
        
        <div class="workflow-grid">
            <!-- Pendientes -->
            <div class="workflow-card workflow-card-orange">
                <div class="workflow-number text-orange-600">{{ $stats['pending'] }}</div>
                <div class="workflow-label text-orange-700">Pendientes</div>
                <div class="workflow-subtitle text-orange-600">Esperando cocina</div>
            </div>

            <!-- En Preparación -->
            <div class="workflow-card workflow-card-blue">
                <div class="workflow-number text-blue-600">{{ $stats['preparing'] }}</div>
                <div class="workflow-label text-blue-700">Preparando</div>
                <div class="workflow-subtitle text-blue-600">En cocina</div>
            </div>

            <!-- Listas -->
            <div class="workflow-card workflow-card-green">
                <div class="workflow-number text-green-600">{{ $stats['ready'] }}</div>
                <div class="workflow-label text-green-700">Listas</div>
                <div class="workflow-subtitle text-green-600">Para servir</div>
            </div>

            <!-- Entregando -->
            <div class="workflow-card workflow-card-yellow">
                <div class="workflow-number text-yellow-600">{{ $stats['delivering'] }}</div>
                <div class="workflow-label text-yellow-700">Entregando</div>
                <div class="workflow-subtitle text-yellow-600">En camino</div>
            </div>
        </div>
    </div>

    <!-- Resumen Rápido -->
    <div class="summary-grid">
        <!-- Alertas -->
        <div class="alert-section">
            <h4 class="alert-title">⚠️ Atención Requerida</h4>
            @if($stats['pending_payments'] > 0)
                <div class="alert-item">
                    <span class="alert-number">{{ $stats['pending_payments'] }}</span>
                    <span class="alert-text"> órdenes sin pagar</span>
                </div>
            @endif
            @if($stats['pending'] > 5)
                <div class="alert-item">
                    <span class="alert-number">{{ $stats['pending'] }}</span>
                    <span class="alert-text"> órdenes esperando cocina</span>
                </div>
            @endif
            @if($stats['ready'] > 3)
                <div class="alert-item">
                    <span class="alert-number">{{ $stats['ready'] }}</span>
                    <span class="alert-text"> órdenes listas sin servir</span>
                </div>
            @endif
            @if($stats['pending_payments'] == 0 && $stats['pending'] <= 5 && $stats['ready'] <= 3)
                <div class="success-text">✅ Todo bajo control</div>
            @endif
        </div>

        <!-- Estado de Mesas -->
        <div class="tables-section">
            <h4 class="tables-title">🪑 Estado de Mesas</h4>
            <div>
                <div class="tables-row">
                    <span class="tables-label">Disponibles:</span>
                    <span class="tables-value">{{ $stats['available_tables'] }}</span>
                </div>
                <div class="tables-row">
                    <span class="tables-label">Ocupadas:</span>
                    <span class="tables-value">{{ $stats['total_tables'] - $stats['available_tables'] }}</span>
                </div>
                <div class="tables-row">
                    <span class="tables-label">Total:</span>
                    <span class="tables-total">{{ $stats['total_tables'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

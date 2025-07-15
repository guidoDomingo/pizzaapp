<x-layouts.app>
    <x-slot name="title">Crear Pedido - Sistema de Pizza</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">🍕 Crear Nuevo Pedido</h1>
                        <p class="text-gray-600">Selecciona productos y mesa para crear una orden</p>
                        <div class="flex items-center mt-3 text-sm text-gray-500">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">● En línea</span>
                            <span class="ml-4">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <a href="/admin" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 shadow-md">
                            ← Volver al Dashboard
                        </a>
                        <a href="/admin/orders" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 shadow-md">
                            📋 Ver Órdenes
                        </a>
                    </div>
                </div>
            </div>

        <!-- Componente Livewire del Carrito -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            @livewire('order-cart')
        </div>

        <!-- Footer con información útil -->
        <div class="mt-8 text-center">
            <div class="bg-white/50 backdrop-blur rounded-xl p-4 inline-block">
                <p class="text-gray-600 text-sm">
                    💡 <strong>Tip:</strong> Selecciona una mesa y agrega productos al carrito para crear el pedido
                </p>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        body {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%);
        }
        
        /* Animaciones suaves */
        .transition-all {
            transition: all 0.3s ease;
        }
        
        /* Mejoras de hover */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }
    </style>
    @endpush
</x-layouts.app>

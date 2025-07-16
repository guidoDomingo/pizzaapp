<x-filament::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">
                    🔥 Órdenes en Cocina
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Gestiona las órdenes pendientes y en preparación
                </p>
            </div>
            
            <div class="p-6">
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament::page>

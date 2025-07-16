<x-filament::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">
                    🍕 Crear Nuevo Pedido
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Complete la información del pedido y agregue los productos
                </p>
            </div>
            
            <form wire:submit.prevent="submit" class="p-6 space-y-6">
                {{ $this->form }}
                
                {{-- Total del pedido --}}
                @if(!empty($this->items))
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-gray-900">Total del Pedido:</span>
                        <span class="text-2xl font-bold text-green-600">
                            ${{ number_format($this->getTotal(), 2) }}
                        </span>
                    </div>
                </div>
                @endif
                
                {{-- Botones de acción --}}
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <button 
                        type="button" 
                        onclick="window.location.reload()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Limpiar
                    </button>
                    
                    <button 
                        type="submit"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Crear Pedido
                    </button>
                </div>
            </form>
        </div>
        
        {{-- Productos disponibles (referencia rápida) --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    📋 Productos Disponibles
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(\App\Models\Product::all() as $product)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $product->description }}</p>
                        <p class="text-lg font-bold text-green-600 mt-2">${{ number_format($product->price, 2) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament::page>

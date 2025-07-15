<div>
    @if($isOpen && $order)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Header -->
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="text-2xl">💳</span>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Procesar Pago - {{ $order->order_number ?? '' }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Mesa {{ $order->table->number ?? 'N/A' }} - Total: ${{ number_format($order->total ?? 0, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <div class="mt-6 space-y-6">
                            <!-- Resumen del Pedido -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3">📋 Resumen del Pedido</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span>Subtotal:</span>
                                        <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Impuestos (18%):</span>
                                        <span>${{ number_format($order->tax ?? 0, 2) }}</span>
                                    </div>
                                    <div class="border-t pt-2 flex justify-between font-bold text-lg">
                                        <span>TOTAL:</span>
                                        <span class="text-green-600">${{ number_format($order->total ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Método de Pago -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="relative">
                                        <input type="radio" wire:model="paymentMethod" value="cash" class="sr-only">
                                        <div class="border-2 rounded-lg p-3 text-center cursor-pointer transition-colors {{ $paymentMethod === 'cash' ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:border-gray-400' }}">
                                            <div class="text-2xl mb-1">💵</div>
                                            <div class="text-xs font-medium">Efectivo</div>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" wire:model="paymentMethod" value="card" class="sr-only">
                                        <div class="border-2 rounded-lg p-3 text-center cursor-pointer transition-colors {{ $paymentMethod === 'card' ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:border-gray-400' }}">
                                            <div class="text-2xl mb-1">💳</div>
                                            <div class="text-xs font-medium">Tarjeta</div>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" wire:model="paymentMethod" value="transfer" class="sr-only">
                                        <div class="border-2 rounded-lg p-3 text-center cursor-pointer transition-colors {{ $paymentMethod === 'transfer' ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:border-gray-400' }}">
                                            <div class="text-2xl mb-1">📱</div>
                                            <div class="text-xs font-medium">Transfer.</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Monto Recibido -->
                            <div>
                                <label for="amount-paid" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $paymentMethod === 'cash' ? 'Monto Recibido' : 'Monto a Cobrar' }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-lg">$</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        min="0"
                                        wire:model="amountPaid"
                                        id="amount-paid"
                                        class="block w-full pl-8 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-lg font-medium"
                                        placeholder="0.00"
                                        {{ $paymentMethod !== 'cash' ? 'readonly' : '' }}
                                    >
                                </div>
                                @error('amountPaid') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Cambio (solo para efectivo) -->
                            @if($paymentMethod === 'cash')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-blue-800">Cambio a entregar:</span>
                                        <span class="text-2xl font-bold text-blue-600">
                                            ${{ number_format($changeAmount, 2) }}
                                        </span>
                                    </div>
                                    @if($changeAmount < 0)
                                        <p class="text-sm text-red-600 mt-1">⚠️ Monto insuficiente</p>
                                    @endif
                                </div>
                            @endif

                            <!-- Referencia de Pago (para tarjeta/transferencia) -->
                            @if($paymentMethod !== 'cash')
                                <div>
                                    <label for="payment-reference" class="block text-sm font-medium text-gray-700 mb-2">
                                        Referencia/Autorización
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="paymentReference"
                                        id="payment-reference"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                        placeholder="Número de autorización..."
                                    >
                                </div>
                            @endif

                            <!-- Notas adicionales -->
                            <div>
                                <label for="payment-notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Notas adicionales (opcional)
                                </label>
                                <textarea 
                                    wire:model="paymentNotes"
                                    id="payment-notes"
                                    rows="2"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                    placeholder="Observaciones del pago..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="button" 
                            wire:click="processPayment"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                            {{ $amountPaid < ($order->total ?? 0) ? 'disabled' : '' }}
                        >
                            💳 Procesar Pago y Generar Ticket
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
            {{ session('error') }}
        </div>
    @endif
</div>

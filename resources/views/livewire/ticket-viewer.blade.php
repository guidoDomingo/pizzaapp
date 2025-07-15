<div>
    @if($isOpen && $ticketData)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeTicket"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold flex items-center">
                                    🧾 Ticket de Venta
                                </h3>
                                <p class="text-green-100 text-sm">{{ $ticketData['ticket_number'] ?? '' }}</p>
                            </div>
                            <button wire:click="closeTicket" class="text-white hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Ticket Content -->
                    <div class="px-6 py-4">
                        <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                            <pre class="text-xs font-mono whitespace-pre-wrap leading-tight">{{ $ticketContent }}</pre>
                        </div>

                        <!-- Información adicional -->
                        @if($order)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                <h4 class="font-medium text-blue-800 mb-2">📋 Información del Pedido</h4>
                                <div class="text-sm space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Mesa:</span>
                                        <span class="font-medium">{{ $order->table->number ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Estado:</span>
                                        <span class="font-medium capitalize">{{ $order->status }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Total:</span>
                                        <span class="font-medium">${{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Método de Pago:</span>
                                        <span class="font-medium capitalize">
                                            @if($order->payment_method === 'cash') Efectivo
                                            @elseif($order->payment_method === 'card') Tarjeta
                                            @elseif($order->payment_method === 'transfer') Transferencia
                                            @else N/A
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Instrucciones -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                            <h4 class="font-medium text-yellow-800 mb-2">💡 Instrucciones</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Entregar este ticket al cliente</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Conservar copia para archivo</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>El pedido ya fue enviado a cocina</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:justify-between gap-3">
                        <div class="flex gap-2">
                            <button 
                                wire:click="printTicket"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                🖨️ Imprimir
                            </button>
                            
                            <button 
                                wire:click="downloadTicket"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                💾 Descargar
                            </button>
                        </div>
                        
                        <button 
                            wire:click="closeTicket"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        >
                            ✅ Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages para el ticket -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
            {{ session('success') }}
        </div>
    @endif
</div>

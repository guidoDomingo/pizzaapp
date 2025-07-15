<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Services\TicketService;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;

class PaymentModal extends Component
{
    public $isOpen = false;
    public $order;
    public $paymentMethod = 'cash';
    public $amountPaid = 0;
    public $changeAmount = 0;
    public $paymentReference = '';
    public $paymentNotes = '';
    
    protected $rules = [
        'paymentMethod' => 'required|in:cash,card,transfer',
        'amountPaid' => 'required|numeric|min:0',
        'paymentReference' => 'nullable|string|max:255',
        'paymentNotes' => 'nullable|string|max:500'
    ];

    protected $listeners = [
        'openPayment' => 'openPayment',
        'orderUpdated' => '$refresh'
    ];

    public function openPayment($orderId)
    {
        // Cargar la orden completa desde la base de datos
        $order = Order::with(['items.product', 'table'])->find($orderId);
        
        if (!$order) {
            session()->flash('error', 'Orden no encontrada.');
            return;
        }
        
        $this->order = $order;
        $this->amountPaid = $this->order->total ?? 0;
        $this->calculateChange();
        $this->isOpen = true;
    }

    public function updatedAmountPaid()
    {
        $this->calculateChange();
    }

    public function calculateChange()
    {
        if ($this->order && $this->paymentMethod === 'cash' && $this->amountPaid >= ($this->order->total ?? 0)) {
            $this->changeAmount = $this->amountPaid - ($this->order->total ?? 0);
        } else {
            $this->changeAmount = 0;
        }
    }

    public function updatedPaymentMethod()
    {
        if ($this->order && $this->paymentMethod !== 'cash') {
            $this->amountPaid = $this->order->total ?? 0;
            $this->changeAmount = 0;
        }
        $this->calculateChange();
    }

    public function processPayment()
    {
        // Validación dinámica basada en el total de la orden
        $this->validate([
            'paymentMethod' => 'required|in:cash,card,transfer',
            'amountPaid' => 'required|numeric|min:' . ($this->order->total ?? 0),
            'paymentReference' => 'nullable|string|max:255',
            'paymentNotes' => 'nullable|string|max:500'
        ]);

        if (!$this->order || $this->amountPaid < $this->order->total) {
            session()->flash('error', 'El monto pagado debe ser mayor o igual al total del pedido.');
            return;
        }

        try {
            DB::beginTransaction();

            // Buscar la orden en la base de datos
            $dbOrder = Order::find($this->order->id);
            
            if (!$dbOrder) {
                session()->flash('error', 'Orden no encontrada.');
                return;
            }

            // Actualizar la orden con la información de pago (sin ticket_number aún)
            $dbOrder->update([
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'paid',
                'amount_paid' => $this->amountPaid,
                'change_amount' => $this->changeAmount,
                'paid_at' => now(),
                'payment_reference' => $this->paymentReference,
                'payment_notes' => $this->paymentNotes,
                'status' => 'pending' // Ahora entra al circuito de cocina
            ]);

            // Generar ticket usando el servicio
            $ticketService = new TicketService();
            $ticketData = $ticketService->generateTicket($dbOrder);

            DB::commit();

            // Emitir eventos para actualizar otros componentes
            $this->emit('paymentCompleted', $dbOrder->id);
            $this->emit('showTicket', $ticketData);

            session()->flash('success', '¡Pago procesado exitosamente! Ticket generado: ' . $ticketData['ticket_number']);
            
            $this->reset(['paymentMethod', 'amountPaid', 'changeAmount', 'paymentReference', 'paymentNotes']);
            $this->order = null;
            $this->isOpen = false;

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->order = null;
        $this->reset(['paymentMethod', 'amountPaid', 'changeAmount', 'paymentReference', 'paymentNotes']);
    }

    public function render()
    {
        return view('livewire.payment-modal');
    }
}

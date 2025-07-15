<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Services\TicketService;

class TicketViewer extends Component
{
    public $isOpen = false;
    public $ticketContent = '';
    public $ticketData = null;
    public $order = null;

    protected $listeners = [
        'showTicket' => 'showTicket',
        'closeTicket' => 'closeTicket'
    ];

    public function showTicket($ticketData)
    {
        $this->ticketData = $ticketData;
        $this->order = Order::with(['items.product', 'table'])->find($ticketData['order']['id']);
        $this->ticketContent = $ticketData['content'];
        $this->isOpen = true;
    }

    public function closeTicket()
    {
        $this->isOpen = false;
        $this->ticketContent = '';
        $this->ticketData = null;
        $this->order = null;
    }

    public function printTicket()
    {
        // Aquí se puede implementar la impresión real
        // Por ahora mostramos un mensaje
        $this->emit('ticketPrinted', $this->ticketData['ticket_number']);
        session()->flash('success', 'Ticket enviado a impresora: ' . $this->ticketData['ticket_number']);
    }

    public function downloadTicket()
    {
        // Crear un archivo temporal para descargar
        $filename = 'ticket_' . $this->ticketData['ticket_number'] . '.txt';
        
        return response()->streamDownload(function () {
            echo $this->ticketContent;
        }, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }

    public function render()
    {
        return view('livewire.ticket-viewer');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\TicketService;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function regenerateTicket($orderId)
    {
        $order = Order::with(['items.product', 'table'])->findOrFail($orderId);
        
        if (!$order->paid_at) {
            return response()->json([
                'error' => 'Esta orden no ha sido pagada aún.'
            ], 400);
        }

        $ticketData = $this->ticketService->generateTicket($order);
        
        return response()->json([
            'success' => true,
            'ticket_data' => $ticketData
        ]);
    }

    public function downloadTicket($orderId)
    {
        $order = Order::with(['items.product', 'table'])->findOrFail($orderId);
        
        if (!$order->paid_at) {
            abort(404, 'Ticket no disponible para órdenes no pagadas.');
        }

        $ticketContent = $this->ticketService->getTicketPreview($order);
        $filename = 'ticket_' . $order->ticket_number . '.txt';
        
        return response()->streamDownload(function () use ($ticketContent) {
            echo $ticketContent;
        }, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }
}

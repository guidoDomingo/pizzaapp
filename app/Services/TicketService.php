<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class TicketService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('ticket');
    }

    public function generateTicket(Order $order)
    {
        $ticket = $this->buildTicketContent($order);
        $ticketNumber = $this->generateTicketNumber();
        
        // Actualizar el número de ticket en la orden
        $order->update([
            'ticket_number' => $ticketNumber,
            'ticket_printed' => true,
        ]);

        return [
            'ticket_number' => $ticketNumber,
            'content' => $ticket,
            'order' => $order->fresh(),
        ];
    }

    protected function buildTicketContent(Order $order)
    {
        $width = $this->config['ticket']['width'];
        $lines = [];
        
        // Header
        $lines[] = $this->centerText($this->config['ticket']['company_name'], $width);
        $lines[] = $this->centerText($this->config['ticket']['company_address'], $width);
        $lines[] = $this->centerText($this->config['ticket']['company_phone'], $width);
        $lines[] = $this->centerText($this->config['ticket']['company_email'], $width);
        $lines[] = str_repeat('=', $width);
        $lines[] = '';
        
        // Order Info
        $lines[] = 'TICKET DE VENTA';
        $lines[] = '';
        $lines[] = 'Fecha: ' . $order->created_at->format('d/m/Y H:i:s');
        $lines[] = 'Orden: ' . $order->order_number;
        $lines[] = 'Ticket: ' . ($order->ticket_number ?? 'TKT-' . time());
        $lines[] = 'Mesa: ' . ($order->table->number ?? 'N/A');
        $lines[] = 'Mesero: ' . ($order->waiter ?? 'Sistema');
        $lines[] = '';
        $lines[] = str_repeat('-', $width);
        
        // Items
        $lines[] = $this->formatItemHeader();
        $lines[] = str_repeat('-', $width);
        
        foreach ($order->items as $item) {
            $lines[] = $this->formatItem($item);
        }
        
        $lines[] = str_repeat('-', $width);
        
        // Totals
        $lines[] = $this->formatTotal('Subtotal:', $order->subtotal);
        $lines[] = $this->formatTotal('IGV (18%):', $order->tax);
        $lines[] = str_repeat('=', $width);
        $lines[] = $this->formatTotal('TOTAL:', $order->total, true);
        
        // Payment Info
        if ($order->amount_paid) {
            $lines[] = '';
            $lines[] = str_repeat('-', $width);
            $lines[] = 'INFORMACIÓN DE PAGO';
            $lines[] = str_repeat('-', $width);
            $lines[] = $this->formatText('Método:', $this->getPaymentMethodName($order->payment_method ?? 'cash'));
            $lines[] = $this->formatTotal('Pagado:', $order->amount_paid);
            
            if ($order->change_amount > 0) {
                $lines[] = $this->formatTotal('Cambio:', $order->change_amount);
            }
            
            if ($order->payment_reference) {
                $lines[] = 'Ref: ' . $order->payment_reference;
            }
        }
        
        // Footer
        $lines[] = '';
        $lines[] = str_repeat('=', $width);
        $lines[] = $this->centerText($this->config['ticket']['footer_message'], $width);
        $lines[] = $this->centerText('Sistema Pizza Express v1.0', $width);
        $lines[] = '';
        $lines[] = $this->centerText('*** CONSERVE ESTE TICKET ***', $width);
        $lines[] = $this->centerText('Gracias por su preferencia', $width);
        $lines[] = '';
        
        return implode("\n", $lines);
    }

    protected function centerText($text, $width)
    {
        $textLength = strlen($text);
        if ($textLength >= $width) {
            return substr($text, 0, $width);
        }
        
        $padding = floor(($width - $textLength) / 2);
        return str_repeat(' ', $padding) . $text;
    }

    protected function formatItemHeader()
    {
        return sprintf("%-20s %3s %6s %8s", "Producto", "Qty", "Precio", "Total");
    }

    protected function formatItem($item)
    {
        $name = substr($item->product->name, 0, 20);
        // Convertir a float para asegurar formato correcto
        $unitPrice = is_numeric($item->unit_price) ? (float) $item->unit_price : 0.0;
        $totalPrice = is_numeric($item->total_price) ? (float) $item->total_price : 0.0;
        $quantity = is_numeric($item->quantity) ? (int) $item->quantity : 0;
        
        return sprintf("%-20s %3d %6.2f %8.2f", 
            $name, 
            $quantity, 
            $unitPrice, 
            $totalPrice
        );
    }

    protected function formatTotal($label, $amount, $bold = false)
    {
        $currency = $this->config['ticket']['currency'];
        // Convertir a float para asegurar que number_format funcione correctamente
        $numericAmount = is_numeric($amount) ? (float) $amount : 0.0;
        $formattedAmount = $currency . number_format($numericAmount, 2);
        
        if ($bold) {
            return sprintf("%30s %12s", strtoupper($label), $formattedAmount);
        }
        
        return sprintf("%30s %12s", $label, $formattedAmount);
    }

    protected function formatText($label, $text)
    {
        return sprintf("%30s %12s", $label, $text);
    }

    protected function getPaymentMethodName($method)
    {
        $methods = [
            'cash' => 'Efectivo',
            'card' => 'Tarjeta',
            'transfer' => 'Transferencia',
        ];
        
        return $methods[$method] ?? 'Efectivo';
    }

    protected function generateTicketNumber()
    {
        return 'TKT-' . Carbon::now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function printTicket($ticketContent)
    {
        // Aquí se implementaría la lógica de impresión
        // Por ahora solo retornamos el contenido para visualización
        return [
            'success' => true,
            'message' => 'Ticket generado exitosamente',
            'content' => $ticketContent,
        ];
    }

    public function getTicketPreview(Order $order)
    {
        return $this->buildTicketContent($order);
    }
}

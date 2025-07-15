<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sistema de Tickets de Venta
    |--------------------------------------------------------------------------
    |
    | Configuración para la generación de tickets de venta
    |
    */

    'ticket' => [
        'width' => 48, // Ancho del ticket en caracteres
        'company_name' => 'Pizza Express',
        'company_address' => 'Av. Principal 123',
        'company_phone' => 'Tel: (01) 234-5678',
        'company_email' => 'info@pizzaexpress.com',
        'footer_message' => '¡Gracias por su visita!',
        'currency' => '$',
        'tax_rate' => 0.18, // 18% IGV
        'print_logo' => true,
        'print_qr' => false,
    ],

    'printer' => [
        'type' => 'thermal', // thermal, laser, dot-matrix
        'driver' => 'escpos', // escpos, cups, windows
        'connection' => 'usb', // usb, network, bluetooth
        'auto_cut' => true,
        'cash_drawer' => false,
    ],

    'receipt_copy' => [
        'customer_copy' => true,
        'kitchen_copy' => true,
        'archive_copy' => true,
    ]
];

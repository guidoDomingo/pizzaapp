<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;

echo "=== Verificando datos ===\n";
echo "Órdenes: " . Order::count() . "\n";
echo "Mesas: " . Table::count() . "\n";
echo "Productos: " . Product::count() . "\n";
echo "Clientes: " . Customer::count() . "\n";

// Crear datos de ejemplo si no existen
if (Order::count() == 0) {
    echo "\n=== Creando datos de ejemplo ===\n";
    
    // Crear mesas si no existen
    if (Table::count() == 0) {
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'number' => "Mesa {$i}",
                'capacity' => rand(2, 6),
                'status' => rand(0, 1) ? 'available' : 'occupied',
                'restaurant_id' => 1
            ]);
        }
        echo "Mesas creadas: " . Table::count() . "\n";
    }
    
    // Crear clientes si no existen
    if (Customer::count() == 0) {
        Customer::create(['name' => 'Cliente General', 'email' => 'general@test.com']);
        Customer::create(['name' => 'Juan Pérez', 'email' => 'juan@test.com', 'phone' => '123456789']);
        Customer::create(['name' => 'María García', 'email' => 'maria@test.com', 'phone' => '987654321']);
        echo "Clientes creados: " . Customer::count() . "\n";
    }
    
    // Crear productos si no existen
    if (Product::count() == 0) {
        Product::create(['name' => 'Pizza Margherita', 'price' => 12.99, 'description' => 'Pizza clásica']);
        Product::create(['name' => 'Pizza Pepperoni', 'price' => 14.99, 'description' => 'Pizza con pepperoni']);
        Product::create(['name' => 'Coca Cola', 'price' => 2.50, 'description' => 'Bebida refrescante']);
        echo "Productos creados: " . Product::count() . "\n";
    }
    
    // Crear órdenes de ejemplo
    $statuses = ['pending', 'preparing', 'ready', 'delivering'];
    
    for ($i = 0; $i < 15; $i++) {
        $order = Order::create([
            'order_number' => 'ORD-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'table_id' => Table::inRandomOrder()->first()->id,
            'status' => $statuses[array_rand($statuses)],
            'total' => rand(1000, 5000) / 100, // Entre $10 y $50
            'paid_at' => rand(0, 1) ? now() : null,
            'created_at' => Carbon::now()->subDays(rand(0, 6))->subHours(rand(0, 23)),
        ]);
    }
    
    echo "Órdenes creadas: " . Order::count() . "\n";
}

echo "\n=== Datos finales ===\n";
echo "Órdenes: " . Order::count() . "\n";
echo "- Pendientes: " . Order::where('status', 'pending')->count() . "\n";
echo "- Preparando: " . Order::where('status', 'preparing')->count() . "\n";
echo "- Listas: " . Order::where('status', 'ready')->count() . "\n";
echo "- Entregando: " . Order::where('status', 'delivering')->count() . "\n";
echo "Mesas disponibles: " . Table::where('status', 'available')->count() . "/" . Table::count() . "\n";
echo "Ventas del día: $" . number_format(Order::whereDate('created_at', today())->sum('total'), 2) . "\n";

echo "\n¡Datos listos para el dashboard!\n";

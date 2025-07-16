<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Table;
use App\Models\Restaurant;

// Obtener el primer restaurante
$restaurant = Restaurant::first();

if (!$restaurant) {
    echo "No hay restaurantes. Creando uno...\n";
    $restaurant = Restaurant::create([
        'name' => 'Pizza House',
        'address' => 'Calle Principal 123',
        'phone' => '123-456-7890',
        'email' => 'info@pizzahouse.com'
    ]);
}

// Actualizar mesas sin restaurant_id
$tablesWithoutRestaurant = Table::whereNull('restaurant_id')->orWhere('restaurant_id', 0)->get();

foreach ($tablesWithoutRestaurant as $table) {
    $table->update(['restaurant_id' => $restaurant->id]);
    echo "Mesa {$table->number} actualizada con restaurant_id: {$restaurant->id}\n";
}

echo "Mesas totales: " . Table::count() . "\n";
echo "Mesas del restaurante: " . Table::where('restaurant_id', $restaurant->id)->count() . "\n";
echo "¡Mesas corregidas!\n";

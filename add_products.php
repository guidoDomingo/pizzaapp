<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Models\Restaurant;

// Obtener el primer restaurante
$restaurant = Restaurant::first();
if (!$restaurant) {
    echo "No hay restaurantes. Creando uno...\n";
    $restaurant = Restaurant::create([
        'name' => 'PizzApp Restaurant',
        'address' => 'Calle Principal 123',
        'phone' => '555-0123'
    ]);
}

// Crear categorías si no existen
$pizzaCategory = Category::firstOrCreate(['name' => 'Pizzas'], ['description' => 'Deliciosas pizzas artesanales']);
$bebidaCategory = Category::firstOrCreate(['name' => 'Bebidas'], ['description' => 'Bebidas refrescantes']);
$entradaCategory = Category::firstOrCreate(['name' => 'Entradas'], ['description' => 'Aperitivos y entradas']);

$productos = [
    // Pizzas
    ['name' => 'Pizza Margherita', 'price' => 12.99, 'description' => 'Salsa de tomate, mozzarella, albahaca fresca', 'category_id' => $pizzaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Pizza Pepperoni', 'price' => 14.99, 'description' => 'Salsa de tomate, mozzarella, pepperoni', 'category_id' => $pizzaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Pizza Hawaiana', 'price' => 15.99, 'description' => 'Salsa de tomate, mozzarella, jamón, piña', 'category_id' => $pizzaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Pizza Cuatro Quesos', 'price' => 16.99, 'description' => 'Mozzarella, parmesano, gorgonzola, provolone', 'category_id' => $pizzaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Pizza Vegetariana', 'price' => 15.99, 'description' => 'Pimientos, champiñones, cebolla, aceitunas', 'category_id' => $pizzaCategory->id, 'restaurant_id' => $restaurant->id],
    
    // Bebidas
    ['name' => 'Coca Cola', 'price' => 2.50, 'description' => 'Bebida cola refrescante 355ml', 'category_id' => $bebidaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Sprite', 'price' => 2.50, 'description' => 'Bebida lima-limón 355ml', 'category_id' => $bebidaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Agua Natural', 'price' => 1.50, 'description' => 'Agua purificada 500ml', 'category_id' => $bebidaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Jugo de Naranja', 'price' => 3.50, 'description' => 'Jugo natural de naranja 300ml', 'category_id' => $bebidaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Cerveza', 'price' => 4.50, 'description' => 'Cerveza nacional 355ml', 'category_id' => $bebidaCategory->id, 'restaurant_id' => $restaurant->id],
    
    // Entradas
    ['name' => 'Pan de Ajo', 'price' => 5.99, 'description' => 'Pan tostado con mantequilla de ajo y hierbas', 'category_id' => $entradaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Alitas BBQ', 'price' => 8.99, 'description' => '8 alitas de pollo con salsa barbacoa', 'category_id' => $entradaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Ensalada César', 'price' => 7.99, 'description' => 'Lechuga, crutones, parmesano, aderezo césar', 'category_id' => $entradaCategory->id, 'restaurant_id' => $restaurant->id],
    ['name' => 'Nachos', 'price' => 6.99, 'description' => 'Totopos con queso derretido y jalapeños', 'category_id' => $entradaCategory->id, 'restaurant_id' => $restaurant->id],
];

echo "Agregando productos...\n";
foreach ($productos as $producto) {
    Product::firstOrCreate(
        ['name' => $producto['name']],
        $producto
    );
}

echo "Productos totales: " . Product::count() . "\n";
echo "Categorías: " . Category::count() . "\n";
echo "¡Productos creados exitosamente!\n";

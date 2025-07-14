<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table as RestaurantTable;
use App\Models\Employee;
use App\Models\Customer;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        // Crear restaurante principal
        $restaurant = Restaurant::create([
            'name' => 'Pizza Express',
            'address' => 'Av. Principal 123, Ciudad',
            'phone' => '+1234567890',
            'email' => 'info@pizzaexpress.com',
            'is_active' => true,
            'settings' => [
                'tax_rate' => 0.18,
                'currency' => 'USD',
                'timezone' => 'America/Lima'
            ]
        ]);

        // Crear categorías
        $categories = [
            ['name' => 'Pizzas', 'description' => 'Deliciosas pizzas artesanales'],
            ['name' => 'Bebidas', 'description' => 'Refrescantes bebidas'],
            ['name' => 'Postres', 'description' => 'Dulces tentaciones'],
            ['name' => 'Entradas', 'description' => 'Para abrir el apetito'],
        ];

        foreach ($categories as $index => $categoryData) {
            $categoryData['restaurant_id'] = $restaurant->id;
            $categoryData['is_active'] = true;
            $categoryData['sort_order'] = $index;
            Category::create($categoryData);
        }

        // Crear productos
        $pizzaCategory = Category::where('name', 'Pizzas')->first();
        $bebidaCategory = Category::where('name', 'Bebidas')->first();

        $products = [
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'Pizza Margherita',
                'description' => 'Tomate, mozzarella y albahaca fresca',
                'price' => 15.90,
                'cost' => 8.50,
                'preparation_time' => 15,
                'ingredients' => ['tomate', 'mozzarella', 'albahaca']
            ],
            [
                'category_id' => $pizzaCategory->id,
                'name' => 'Pizza Pepperoni',
                'description' => 'Tomate, mozzarella y pepperoni',
                'price' => 18.90,
                'cost' => 10.50,
                'preparation_time' => 15,
                'ingredients' => ['tomate', 'mozzarella', 'pepperoni']
            ],
            [
                'category_id' => $bebidaCategory->id,
                'name' => 'Coca Cola',
                'description' => 'Refrescante gaseosa 350ml',
                'price' => 3.50,
                'cost' => 1.20,
                'preparation_time' => 2,
                'ingredients' => []
            ],
        ];

        foreach ($products as $productData) {
            $productData['restaurant_id'] = $restaurant->id;
            $productData['is_available'] = true;
            Product::create($productData);
        }

        // Crear mesas
        for ($i = 1; $i <= 10; $i++) {
            RestaurantTable::create([
                'restaurant_id' => $restaurant->id,
                'number' => 'Mesa ' . $i,
                'capacity' => rand(2, 6),
                'status' => 'available',
                'qr_code' => 'QR-' . $i
            ]);
        }

        // Crear empleados
        $employees = [
            [
                'employee_code' => 'EMP001',
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'email' => 'juan.perez@pizzaexpress.com',
                'phone' => '+1234567891',
                'position' => 'manager',
                'salary' => 1500.00,
                'hire_date' => now()->subMonths(12)
            ],
            [
                'employee_code' => 'EMP002',
                'first_name' => 'María',
                'last_name' => 'García',
                'email' => 'maria.garcia@pizzaexpress.com',
                'phone' => '+1234567892',
                'position' => 'waiter',
                'salary' => 800.00,
                'hire_date' => now()->subMonths(6)
            ],
            [
                'employee_code' => 'EMP003',
                'first_name' => 'Carlos',
                'last_name' => 'Rodríguez',
                'email' => 'carlos.rodriguez@pizzaexpress.com',
                'phone' => '+1234567893',
                'position' => 'chef',
                'salary' => 1200.00,
                'hire_date' => now()->subMonths(18)
            ],
        ];

        foreach ($employees as $employeeData) {
            $employeeData['restaurant_id'] = $restaurant->id;
            $employeeData['is_active'] = true;
            Employee::create($employeeData);
        }

        // Crear algunos clientes
        $customers = [
            [
                'name' => 'Ana López',
                'email' => 'ana.lopez@email.com',
                'phone' => '+1234567894',
                'address' => 'Calle Secundaria 456'
            ],
            [
                'name' => 'Pedro Martínez',
                'email' => 'pedro.martinez@email.com',
                'phone' => '+1234567895',
                'address' => 'Av. Central 789'
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
}

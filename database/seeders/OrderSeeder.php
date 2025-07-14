<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table as RestaurantTable;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Restaurant;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurant = Restaurant::first();
        
        // Crear algunos clientes si no existen
        $customers = [
            [
                'name' => 'María García Pizza Lover',
                'email' => 'maria.pizza@email.com',
                'phone' => '+1234567896',
                'address' => 'Av. Pizza 123'
            ],
            [
                'name' => 'Carlos Pizza Rodríguez',
                'email' => 'carlos.pizza@email.com',
                'phone' => '+1234567897',
                'address' => 'Calle Pizza Especial 456'
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::firstOrCreate(['email' => $customerData['email']], $customerData);
        }

        // Crear órdenes de prueba
        $pizzaProducts = Product::where('name', 'like', '%Pizza%')->get();
        $customers = Customer::all();
        $tables = RestaurantTable::take(3)->get();

        for ($i = 1; $i <= 5; $i++) {
            $customer = $customers->random();
            $table = $tables->random();
            
            $order = Order::create([
                'restaurant_id' => $restaurant->id,
                'table_id' => $table->id,
                'customer_id' => $customer->id,
                'order_number' => 'PIZZA-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => collect(['pending', 'preparing', 'ready'])->random(),
                'order_type' => 'dine_in',
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0,
                'payment_status' => 'pending',
                'notes' => 'Orden de pizza especial #' . $i
            ]);

            $total = 0;

            // Agregar productos a la orden
            foreach ($pizzaProducts->take(rand(1, 3)) as $product) {
                $quantity = rand(1, 3);
                $subtotal = $product->price * $quantity;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_price' => $subtotal
                ]);
            }

            // Actualizar totales
            $tax = $total * 0.18;
            $order->update([
                'subtotal' => $total,
                'tax' => $tax,
                'total' => $total + $tax
            ]);
        }
    }
}

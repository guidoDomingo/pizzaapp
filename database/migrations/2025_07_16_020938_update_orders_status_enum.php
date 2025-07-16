<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero, actualizar cualquier valor 'completed' existente a 'delivered'
        DB::table('orders')
            ->where('status', 'completed')
            ->update(['status' => 'delivered']);
        
        // Luego modificar el enum para incluir 'completed'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'preparing', 'ready', 'delivered', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convertir 'completed' de vuelta a 'delivered' antes de revertir
        DB::table('orders')
            ->where('status', 'completed')
            ->update(['status' => 'delivered']);
            
        // Revertir al enum original
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
};

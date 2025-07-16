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
        // Agregar 'delivering' al enum de status
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'preparing', 'ready', 'delivering', 'delivered', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convertir 'delivering' a 'delivered' antes de revertir
        DB::table('orders')
            ->where('status', 'delivering')
            ->update(['status' => 'delivered']);
            
        // Revertir al enum anterior
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'preparing', 'ready', 'delivered', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};

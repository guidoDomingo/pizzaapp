<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Modificar el enum para incluir 'delivering'
            $table->enum('status', ['pending', 'preparing', 'ready', 'delivering', 'delivered', 'cancelled'])
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revertir al enum original
            $table->enum('status', ['pending', 'preparing', 'ready', 'delivered', 'cancelled'])
                  ->default('pending')
                  ->change();
        });
    }
};

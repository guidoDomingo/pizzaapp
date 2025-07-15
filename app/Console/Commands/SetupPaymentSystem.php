<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupPaymentSystem extends Command
{
    protected $signature = 'pizza:setup-payment';
    protected $description = 'Configurar el sistema de pagos ejecutando las migraciones necesarias';

    public function handle()
    {
        $this->info('🚀 Configurando Sistema de Pagos...');
        
        try {
            // Ejecutar migraciones específicas
            $this->info('📦 Ejecutando migración de campos de pago...');
            Artisan::call('migrate', [
                '--path' => 'database/migrations/2025_07_15_131000_add_payment_fields_to_orders_table.php',
                '--force' => true
            ]);
            
            $this->info('📦 Ejecutando migración de estado cart...');
            Artisan::call('migrate', [
                '--path' => 'database/migrations/2025_07_15_174700_add_cart_status_to_orders_table.php', 
                '--force' => true
            ]);
            
            $this->info('📦 Ejecutando todas las migraciones pendientes...');
            Artisan::call('migrate', ['--force' => true]);
            
            $this->info('✅ ¡Sistema de pagos configurado exitosamente!');
            $this->info('💳 Ahora puedes usar el modal de pago en el sistema');
            
        } catch (\Exception $e) {
            $this->error('❌ Error al configurar el sistema: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}

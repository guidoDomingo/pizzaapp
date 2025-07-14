<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for Filament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Verificar si ya existe el usuario
        $existingUser = User::where('email', 'juan.perez@pizzaexpress.com')->first();
        
        if ($existingUser) {
            $this->info('El usuario administrador ya existe.');
            $this->info('Email: juan.perez@pizzaexpress.com');
            $this->info('Password: password123');
            return;
        }

        // Crear usuario administrador
        $user = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@pizzaexpress.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $this->info('¡Usuario administrador creado exitosamente!');
        $this->info('Email: juan.perez@pizzaexpress.com');
        $this->info('Password: password123');
        $this->info('Ahora puedes acceder al panel de administración en /admin');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $almaceneroRole = Role::where('name', 'almacenero')->first();
        $puntoRole = Role::where('name', 'punto')->first();

        // Create admin user
        User::firstOrCreate([
            'email' => 'admin@inventory.com'
        ], [
            'name' => 'Administrador',
            'email' => 'admin@inventory.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        // Create warehouse managers
        User::firstOrCreate([
            'email' => 'almacenero1@inventory.com'
        ], [
            'name' => 'Juan Pérez - Almacenero',
            'email' => 'almacenero1@inventory.com',
            'password' => Hash::make('password'),
            'role_id' => $almaceneroRole->id,
            'is_active' => true,
        ]);

        User::firstOrCreate([
            'email' => 'almacenero2@inventory.com'
        ], [
            'name' => 'María García - Almacenero',
            'email' => 'almacenero2@inventory.com',
            'password' => Hash::make('password'),
            'role_id' => $almaceneroRole->id,
            'is_active' => true,
        ]);

        // Create sales point managers
        User::firstOrCreate([
            'email' => 'punto1@inventory.com'
        ], [
            'name' => 'Carlos López - Punto de Venta',
            'email' => 'punto1@inventory.com',
            'password' => Hash::make('password'),
            'role_id' => $puntoRole->id,
            'is_active' => true,
        ]);

        User::firstOrCreate([
            'email' => 'punto2@inventory.com'
        ], [
            'name' => 'Ana Martínez - Punto de Venta',
            'email' => 'punto2@inventory.com',
            'password' => Hash::make('password'),
            'role_id' => $puntoRole->id,
            'is_active' => true,
        ]);
    }
}
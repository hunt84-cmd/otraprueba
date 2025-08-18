<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrador del sistema - gestiona usuarios, almacenes, puntos de venta y productos',
            ],
            [
                'name' => 'almacenero',
                'description' => 'Encargado de almacén - gestiona inventario y envíos a puntos de venta',
            ],
            [
                'name' => 'punto',
                'description' => 'Encargado de punto de venta - gestiona ventas y recepción de productos',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\SalesPoint;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create warehouses
        $almacenero1 = User::where('email', 'almacenero1@inventory.com')->first();
        $almacenero2 = User::where('email', 'almacenero2@inventory.com')->first();

        $warehouse1 = Warehouse::firstOrCreate([
            'code' => 'ALM001'
        ], [
            'name' => 'Almacén Central Norte',
            'code' => 'ALM001',
            'address' => 'Av. Industrial 123, Zona Norte',
            'phone' => '+1234567890',
            'manager_id' => $almacenero1->id,
            'is_active' => true,
        ]);

        $warehouse2 = Warehouse::firstOrCreate([
            'code' => 'ALM002'
        ], [
            'name' => 'Almacén Central Sur',
            'code' => 'ALM002',
            'address' => 'Calle Comercial 456, Zona Sur',
            'phone' => '+1234567891',
            'manager_id' => $almacenero2->id,
            'is_active' => true,
        ]);

        // Create sales points
        $punto1 = User::where('email', 'punto1@inventory.com')->first();
        $punto2 = User::where('email', 'punto2@inventory.com')->first();

        SalesPoint::firstOrCreate([
            'code' => 'PV001'
        ], [
            'name' => 'Punto de Venta Centro',
            'code' => 'PV001',
            'address' => 'Plaza Central, Local 15',
            'phone' => '+1234567892',
            'warehouse_id' => $warehouse1->id,
            'manager_id' => $punto1->id,
            'is_active' => true,
        ]);

        SalesPoint::firstOrCreate([
            'code' => 'PV002'
        ], [
            'name' => 'Punto de Venta Mall',
            'code' => 'PV002',
            'address' => 'Centro Comercial Plaza, Local 23',
            'phone' => '+1234567893',
            'warehouse_id' => $warehouse1->id,
            'manager_id' => $punto2->id,
            'is_active' => true,
        ]);

        SalesPoint::firstOrCreate([
            'code' => 'PV003'
        ], [
            'name' => 'Punto de Venta Sur',
            'code' => 'PV003',
            'address' => 'Av. Sur 789, Local 5',
            'phone' => '+1234567894',
            'warehouse_id' => $warehouse2->id,
            'manager_id' => $punto1->id,
            'is_active' => true,
        ]);

        // Create products
        $products = [
            [
                'code' => 'PROD001',
                'name' => 'Arroz Blanco Premium',
                'unit' => 'kg',
                'description' => 'Arroz blanco de grano largo, calidad premium',
            ],
            [
                'code' => 'PROD002',
                'name' => 'Frijoles Negros',
                'unit' => 'kg',
                'description' => 'Frijoles negros seleccionados',
            ],
            [
                'code' => 'PROD003',
                'name' => 'Aceite Vegetal',
                'unit' => 'unidad',
                'description' => 'Aceite vegetal 1 litro',
            ],
            [
                'code' => 'PROD004',
                'name' => 'Azúcar Refinada',
                'unit' => 'kg',
                'description' => 'Azúcar blanca refinada',
            ],
            [
                'code' => 'PROD005',
                'name' => 'Harina de Trigo',
                'unit' => 'kg',
                'description' => 'Harina de trigo todo uso',
            ],
            [
                'code' => 'PROD006',
                'name' => 'Leche en Polvo',
                'unit' => 'lb',
                'description' => 'Leche en polvo entera',
            ],
            [
                'code' => 'PROD007',
                'name' => 'Pasta Espagueti',
                'unit' => 'unidad',
                'description' => 'Pasta espagueti 500g',
            ],
            [
                'code' => 'PROD008',
                'name' => 'Sal de Mesa',
                'unit' => 'kg',
                'description' => 'Sal refinada de mesa',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['code' => $product['code']], $product);
        }
    }
}
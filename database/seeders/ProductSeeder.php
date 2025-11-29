<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Dell XPS 15',
                'price' => 1299.99,
                'description' => 'Laptop de alto rendimiento con procesador Intel i7 y 16GB de RAM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mouse Logitech MX Master 3',
                'price' => 99.99,
                'description' => 'Mouse inalámbrico ergonómico con sensor de alta precisión',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teclado Mecánico RGB',
                'price' => 149.99,
                'description' => 'Teclado mecánico con switches Cherry MX y retroiluminación RGB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monitor Samsung 27" 4K',
                'price' => 399.99,
                'description' => 'Monitor 4K UHD con tecnología HDR y tiempo de respuesta de 1ms',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Auriculares Sony WH-1000XM4',
                'price' => 349.99,
                'description' => 'Auriculares inalámbricos con cancelación de ruido activa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}

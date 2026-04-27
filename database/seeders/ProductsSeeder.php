<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            DB::table('products')->insert([
                'nombre' => "Producto $i",
                'descripcion' => "Descripción del producto $i",
                'price_cents' => rand(500, 50000),
                'stock' => rand(0, 20),
                'activo' => true,
                'images' => json_encode(["https://picsum.photos/seed/product$i/800/600"]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

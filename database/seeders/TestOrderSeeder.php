<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestOrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create(['email' => 'buyer@example.com', 'name' => 'Buyer']);

        $product = \App\Models\Product::first();
        if (! $product) {
            return;
        }

        $order = \App\Models\Order::create([
            'usuario_id' => $user->id,
            'direccion_id' => null,
            'descuento_id' => null,
            'estado' => 'pending',
            'precio_total_cents' => $product->price_cents,
            'fecha' => now(),
        ]);

        \App\Models\OrderLine::create([
            'pedido_id' => $order->id,
            'producto_id' => $product->id,
            'cantidad' => 1,
            'precio_unitario_cents' => $product->price_cents,
        ]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_simulated_checkout_creates_paid_order()
    {
        $this->seed();

        $user = \App\Models\User::first();
        $product = \App\Models\Product::first();

        $this->actingAs($user);
        // Add to cart
        $this->post(route('cart.add'), ['producto_id' => $product->id])->assertRedirect();

        // Proceed to checkout (simulated because no stripe secret)
        $this->post(route('checkout.create'))->assertRedirect();

        $this->assertDatabaseHas('orders', ['usuario_id' => $user->id, 'estado' => 'paid']);
    }
}

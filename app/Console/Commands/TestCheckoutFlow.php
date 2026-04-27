<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class TestCheckoutFlow extends Command
{
    protected $signature = 'test:checkout-flow';
    protected $description = 'Run a simulated checkout flow for the first user';

    public function handle()
    {
        $user = \App\Models\User::first();
        if (! $user) {
            $this->error('No user found');
            return 1;
        }

        $product = \App\Models\Product::first();
        if (! $product) {
            $this->error('No product found');
            return 1;
        }

        // Create cart and line
        $cart = \App\Models\Cart::firstOrCreate(['usuario_id' => $user->id]);
        \App\Models\CartLine::updateOrCreate(
            ['cart_id' => $cart->id, 'producto_id' => $product->id],
            ['cantidad' => 1, 'price_snapshot_cents' => $product->price_cents]
        );

        // Create a request impersonating the user
        $req = Request::create('/checkout/create', 'POST');
        $req->setUserResolver(function () use ($user) { return $user; });

        $controller = new \App\Http\Controllers\CheckoutController();
        $response = $controller->create($req);

        $this->info('Checkout flow executed. Response type: '.get_class($response));

        $order = \App\Models\Order::latest()->first();
        $this->info('Latest order id: '.$order->id.' estado: '.$order->estado);

        return 0;
    }
}

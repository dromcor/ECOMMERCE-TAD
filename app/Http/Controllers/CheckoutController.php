<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // Pay for an existing order (kept for testing)
    public function pay($orderId)
    {
        $order = \App\Models\Order::with('lines.product', 'user')->findOrFail($orderId);

        $stripe = new StripeClient(config('services.stripe.secret'));

        $line_items = [];
        foreach ($order->lines as $line) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $line->producto->nombre ?? 'Producto'],
                    'unit_amount' => $line->precio_unitario_cents,
                ],
                'quantity' => $line->cantidad,
            ];
        }

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'client_reference_id' => (string) $order->id,
            'line_items' => $line_items,
            'success_url' => url('/checkout/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/checkout/cancel'),
        ]);

        return redirect($session->url);
    }

    // Create an Order from the authenticated user's cart and redirect to Stripe Checkout
    public function create(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $cart = \App\Models\Cart::where('usuario_id', $user->id)->with('lines.product')->first();
        if (! $cart || $cart->lines->isEmpty()) {
            return back()->with('error', 'El carrito está vacío');
        }

        // Pre-validate stock
        foreach ($cart->lines as $line) {
            if ($line->cantidad > $line->product->stock) {
                return back()->with('error', 'Stock insuficiente para '.$line->product->nombre);
            }
        }

        // Create order within transaction
        $order = DB::transaction(function () use ($user, $cart) {
            $total = 0;
            $order = \App\Models\Order::create([
                'usuario_id' => $user->id,
                'direccion_id' => null,
                'descuento_id' => null,
                'estado' => 'pending',
                'precio_total_cents' => 0,
                'fecha' => now(),
            ]);

            foreach ($cart->lines as $line) {
                $price = $line->price_snapshot_cents;
                $qty = $line->cantidad;

                \App\Models\OrderLine::create([
                    'pedido_id' => $order->id,
                    'producto_id' => $line->producto_id,
                    'cantidad' => $qty,
                    'precio_unitario_cents' => $price,
                ]);

                // Decrement stock atomically
                $affected = DB::table('products')
                    ->where('id', $line->producto_id)
                    ->where('stock', '>=', $qty)
                    ->decrement('stock', $qty);

                if ($affected === 0) {
                    // Force rollback
                    throw new \Exception('Stock insuficiente para producto ID '.$line->producto_id);
                }

                $total += $price * $qty;
            }

            // Update order total
            $order->precio_total_cents = $total;
            $order->save();

            // Clear the cart
            \App\Models\CartLine::where('cart_id', $cart->id)->delete();
            $cart->delete();

            // Create payment provisional
            DB::table('payments')->insert([
                'pedido_id' => $order->id,
                'metodo' => 'stripe',
                'amount_cents' => $total,
                'status' => 'pending',
                'provider_ref' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $order;
        });

        // If Stripe is not configured, simulate immediate success for local/testing
        $stripeSecret = config('services.stripe.secret');
        if (empty($stripeSecret)) {
            // Mark payment succeeded and order as paid
            DB::table('payments')->where('pedido_id', $order->id)->update(['status' => 'succeeded', 'provider_ref' => 'local_simulation', 'updated_at' => now()]);
            DB::table('orders')->where('id', $order->id)->update(['estado' => 'paid', 'status_id' => DB::table('order_statuses')->where('nombre', 'paid')->value('id'), 'updated_at' => now()]);

            // Send confirmation email immediately in local
            try {
                $order->load('lines.product', 'user');
                if ($order->user && app()->environment('local')) {
                    \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderConfirmed($order));
                } elseif ($order->user) {
                    \Illuminate\Support\Facades\Mail::to($order->user->email)->queue(new \App\Mail\OrderConfirmed($order));
                }
            } catch (\Exception $ex) {
                Log::error('Failed to send simulated order email: '.$ex->getMessage());
            }

            return redirect()->route('checkout.success', ['session_id' => 'local_simulated']);
        }

        // Create Stripe Checkout Session
        $stripe = new StripeClient($stripeSecret);

        $line_items = [];
        $order->load('lines.product');
        foreach ($order->lines as $line) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $line->producto->nombre ?? 'Producto'],
                    'unit_amount' => $line->precio_unitario_cents,
                ],
                'quantity' => $line->cantidad,
            ];
        }

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'client_reference_id' => (string) $order->id,
            'line_items' => $line_items,
            'success_url' => url('/checkout/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/checkout/cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        return view('checkout.success', compact('sessionId'));
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}

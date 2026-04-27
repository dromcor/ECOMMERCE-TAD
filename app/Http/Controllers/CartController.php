<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartLine;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|integer|exists:products,id',
            'cantidad' => 'sometimes|integer|min:1',
        ]);

        $productId = $request->input('producto_id');
        $cantidad = (int) $request->input('cantidad', 1);

        // Support guest carts using a session cookie 'cart_session'
        $user = $request->user();
        if ($user) {
            $cart = Cart::firstOrCreate(['usuario_id' => $user->id]);
        } else {
            $sessionToken = $request->cookie('cart_session') ?: bin2hex(random_bytes(12));
            $cart = Cart::firstOrCreate(['session_id' => $sessionToken]);
            // ensure cookie present
            cookie()->queue(cookie('cart_session', $sessionToken, 60 * 24 * 30));
        }

        $line = CartLine::where('cart_id', $cart->id)->where('producto_id', $productId)->first();
        if ($line) {
            $line->cantidad += $cantidad;
            $line->save();
        } else {
            $product = \App\Models\Product::find($productId);
            CartLine::create([
                'cart_id' => $cart->id,
                'producto_id' => $productId,
                'cantidad' => $cantidad,
                'price_snapshot_cents' => $product->price_cents,
            ]);
        }

        return back()->with('success', 'Producto añadido al carrito');
    }
}

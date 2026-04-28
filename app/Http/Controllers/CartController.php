<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartLine;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Añade un producto al carrito del usuario.
     */
    public function add(Request $request)
    {
        $request->validate([
            'producto_id' => 'nullable|integer|exists:products,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'cantidad' => 'nullable|integer|min:1',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $productoId = $request->input('producto_id');

        if (!$productoId) {
            $productoId = $request->input('product_id');
        }

        if (!$productoId) {
            return back()->with('error', 'No se ha seleccionado ningún producto.');
        }

        $cantidad = $request->input('cantidad');

        if (!$cantidad) {
            $cantidad = $request->input('quantity', 1);
        }

        $cantidad = (int) $cantidad;

        $producto = Product::findOrFail($productoId);

        $carrito = Cart::firstOrCreate([
            'usuario_id' => auth()->id(),
        ]);

        $linea = CartLine::where('cart_id', $carrito->id)
            ->where('producto_id', $producto->id)
            ->first();

        if ($linea) {
            $linea->cantidad = $linea->cantidad + $cantidad;
            $linea->save();
        } else {
            CartLine::create([
                'cart_id' => $carrito->id,
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'price_snapshot_cents' => $producto->price_cents,
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Producto añadido al carrito.');
    }

    /**
     * Suma una unidad a una línea del carrito.
     */
    public function increase(CartLine $line)
    {
        $this->comprobarLineaDelUsuario($line);

        $line->cantidad = $line->cantidad + 1;
        $line->save();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cantidad actualizada.');
    }

    /**
     * Resta una unidad a una línea del carrito.
     * Si la cantidad llega a 0, se elimina la línea.
     */
    public function decrease(CartLine $line)
    {
        $this->comprobarLineaDelUsuario($line);

        if ($line->cantidad > 1) {
            $line->cantidad = $line->cantidad - 1;
            $line->save();
        } else {
            $line->delete();
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cantidad actualizada.');
    }

    /**
     * Elimina completamente un producto del carrito.
     */
    public function remove(CartLine $line)
    {
        $this->comprobarLineaDelUsuario($line);

        $line->delete();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Evita que un usuario pueda modificar líneas de otro usuario.
     */
    private function comprobarLineaDelUsuario(CartLine $line)
    {
        $carrito = $line->cart;

        if (!$carrito || $carrito->usuario_id !== auth()->id()) {
            abort(403);
        }
    }
}
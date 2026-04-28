@extends('layouts.app')

@section('title', 'Carrito | Birra Market')

@section('content')
@php
    $cart = \App\Models\Cart::where('usuario_id', auth()->id())
        ->with('lines.product')
        ->first();

    $total = 0;
@endphp

<section class="admin-section">
    <div class="container">
        <div class="admin-header-row">
            <div>
                <span>Compra online</span>
                <h1>Tu carrito</h1>
                <p>Revisa las cervezas añadidas antes de confirmar el pedido.</p>
            </div>

            <a href="{{ route('products.index') }}" class="primary-button">Seguir comprando</a>
        </div>

        @if(session('success'))
            <div class="alert success-alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error-alert">{{ session('error') }}</div>
        @endif

        @if(!$cart || $cart->lines->isEmpty())
            <div class="empty-box">
                <h3>El carrito está vacío</h3>
                <p>Añade alguna cerveza desde el catálogo para empezar tu compra.</p>
            </div>
        @else
            <div class="table-box cart-table-box">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cart->lines as $line)
                            @php
                                $lineTotal = $line->price_snapshot_cents * $line->cantidad;
                                $total = $total + $lineTotal;
                            @endphp

                            <tr>
                                <td>
                                    <strong>{{ $line->product->nombre }}</strong>
                                    <br>
                                    <span class="table-muted">
                                        {{ \Illuminate\Support\Str::limit($line->product->descripcion, 70) }}
                                    </span>
                                </td>

                                <td>
                                    {{ number_format($line->price_snapshot_cents / 100, 2, ',', '.') }} €
                                </td>

                                <td>
                                    <div class="quantity-controls">
                                        <form action="{{ route('cart.decrease', $line) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="quantity-button">-</button>
                                        </form>

                                        <span>{{ $line->cantidad }}</span>

                                        <form action="{{ route('cart.increase', $line) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="quantity-button">+</button>
                                        </form>
                                    </div>
                                </td>

                                <td>
                                    <strong>{{ number_format($lineTotal / 100, 2, ',', '.') }} €</strong>
                                </td>

                                <td>
                                    <form action="{{ route('cart.remove', $line) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger-button">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-summary">
                <div>
                    <span>Total del carrito</span>
                    <strong>{{ number_format($total / 100, 2, ',', '.') }} €</strong>
                </div>

                <form action="{{ route('checkout.create') }}" method="POST">
                    @csrf
                    <button type="submit" class="auth-button">Proceder a pagar</button>
                </form>
            </div>
        @endif
    </div>
</section>
@endsection
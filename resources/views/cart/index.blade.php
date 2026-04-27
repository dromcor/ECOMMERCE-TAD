@extends('layouts.app')

@section('content')
<h2>Tu carrito</h2>
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@auth
  @php $cart = \App\Models\Cart::where('usuario_id', auth()->id())->with('lines.product')->first(); @endphp
  @if(! $cart || $cart->lines->isEmpty())
    <p>El carrito está vacío.</p>
  @else
    <table class="table">
      <thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th></tr></thead>
      <tbody>
        @php $sum = 0; @endphp
        @foreach($cart->lines as $line)
          <tr>
            <td>{{ $line->product->nombre }}</td>
            <td>{{ number_format($line->price_snapshot_cents/100, 2) }} €</td>
            <td>{{ $line->cantidad }}</td>
            <td>{{ number_format(($line->price_snapshot_cents * $line->cantidad)/100, 2) }} €</td>
          </tr>
          @php $sum += $line->price_snapshot_cents * $line->cantidad; @endphp
        @endforeach
      </tbody>
    </table>

    <p class="lead">Total: {{ number_format($sum/100, 2) }} €</p>

    <form method="POST" action="{{ route('checkout.create') }}">
      @csrf
      <button class="btn btn-primary">Proceder a pagar</button>
    </form>
  @endif
@endauth

@guest
  <p>Debes <a href="{{ route('login') }}">iniciar sesión</a> para ver tu carrito y proceder al pago.</p>
@endguest

@endsection

@extends('layouts.app')

@section('content')
<div class="mt-5">
  <h2>Pago completado</h2>
  <p>Gracias. Tu pago ha sido procesado correctamente.</p>

  @auth
    @php $order = App\Models\Order::where('usuario_id', auth()->id())->where('estado', 'paid')->latest()->with('lines.product')->first(); @endphp
    @if($order)
      <h4>Pedido #{{ $order->id }}</h4>
      <ul>
        @foreach($order->lines as $line)
          <li>{{ $line->cantidad }} x {{ $line->producto->nombre ?? 'producto' }} — {{ number_format($line->precio_unitario_cents/100,2) }} €</li>
        @endforeach
      </ul>
      <p>Total: {{ number_format($order->precio_total_cents/100,2) }} €</p>
    @endif
  @endauth

  <p>Session: {{ $sessionId }}</p>
</div>
@endsection

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Confirmación de pedido</title>
  </head>
  <body>
    <h2>Gracias por tu compra</h2>
    <p>Hola {{ $order->user->name ?? 'cliente' }},</p>
    <p>Hemos recibido tu pedido <strong>#{{ $order->id }}</strong> con un total de <strong>{{ number_format($order->precio_total_cents/100, 2) }} €</strong>.</p>
    <h3>Detalle</h3>
    <ul>
      @foreach($order->lines as $line)
        <li>{{ $line->cantidad }} x {{ $line->producto->nombre ?? 'producto' }} — {{ number_format($line->precio_unitario_cents/100, 2) }} €</li>
      @endforeach
    </ul>
    <p>Estado actual: {{ $order->estado }}</p>
    <p>Gracias por confiar en nosotros.</p>
  </body>
  </html>
